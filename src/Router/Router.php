<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Router\Event\BeginRequestEvent;
use TQ\ExtDirect\Router\Event\EndRequestEvent;
use TQ\ExtDirect\Router\Event\ExceptionEvent;
use TQ\ExtDirect\Router\Event\InvokeEvent;
use TQ\ExtDirect\Router\Event\ServiceResolveEvent;

/**
 * Class Router
 *
 * @package TQ\ExtDirect\Router
 */
class Router
{
    /**
     * @var ServiceResolverInterface
     */
    private $serviceResolver;

    /**
     * @var null|EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param ServiceResolverInterface      $serviceResolver
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param bool                          $debug
     */
    public function __construct(
        ServiceResolverInterface $serviceResolver,
        EventDispatcherInterface $eventDispatcher = null,
        $debug = false
    ) {
        $this->serviceResolver = $serviceResolver;
        $this->eventDispatcher = $eventDispatcher;
        $this->debug           = $debug;
    }

    /**
     * @return EventDispatcherInterface|null
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @param RequestCollection $directRequest
     * @param HttpRequest       $httpRequest
     * @return ResponseCollection
     */
    public function handle(RequestCollection $directRequest, HttpRequest $httpRequest)
    {
        $this->dispatchEvent(
            RouterEvents::BEGIN_REQUEST,
            new BeginRequestEvent(
                $directRequest,
                $httpRequest
            )
        );

        $invocations = $this->prepareInvocation($directRequest, $httpRequest);
        $responses   = array();
        foreach ($invocations as $invocation) {
            /** @var ServiceReference|Response $service */
            /** @var array|\Exception $arguments */
            list($service, $arguments, $singleRequest) = $invocation;

            // if $service is a response, then an exception occurred during invocation preparation
            if ($service instanceof Response) {
                $responses[] = $service;
                continue;
            }

            try {
                $result = $this->invokeService($service, $arguments, $singleRequest, $httpRequest);

                if ($result instanceof Response) {
                    $responses[] = $result;
                } else {
                    $responses[] = RPCResponse::fromRequest($singleRequest, $result);
                }
            } catch (\Exception $e) {
                /** @var ExceptionEvent $exceptionEvent */
                $exceptionEvent = $this->dispatchEvent(
                    RouterEvents::EXCEPTION,
                    new ExceptionEvent(
                        $singleRequest,
                        $httpRequest,
                        $e,
                        ExceptionResponse::fromRequest($singleRequest, $e, $this->debug),
                        isset($service) ? $service : null,
                        isset($arguments) ? $arguments : null
                    )
                );

                $responses[] = $exceptionEvent->getResponse();
            }
        }

        /** @var EndRequestEvent $endRequestEvent */
        $endRequestEvent = $this->dispatchEvent(
            RouterEvents::END_REQUEST,
            new EndRequestEvent(
                $directRequest,
                new ResponseCollection($responses),
                $httpRequest
            )
        );

        return $endRequestEvent->getDirectResponse();
    }

    /**
     * @param RequestCollection $directRequest
     * @param HttpRequest       $httpRequest
     * @return array
     */
    protected function prepareInvocation(RequestCollection $directRequest, HttpRequest $httpRequest)
    {
        $invocations  = array();
        $closeSession = true;
        foreach ($directRequest as $singleRequest) {
            /** @var Request $singleRequest */
            try {
                /** @var ServiceReference $service */
                /** @var array $arguments */
                list($service, $arguments) = $this->getInvocationParameters($singleRequest, $httpRequest);
                if ($closeSession && $service->hasSession()) {
                    $closeSession = false;
                }
                $invocations[] = array($service, $arguments, $singleRequest);
            } catch (\Exception $e) {
                /** @var ExceptionEvent $exceptionEvent */
                $exceptionEvent = $this->dispatchEvent(
                    RouterEvents::EXCEPTION,
                    new ExceptionEvent(
                        $singleRequest,
                        $httpRequest,
                        $e,
                        ExceptionResponse::fromRequest($singleRequest, $e, $this->debug),
                        null,
                        null
                    )
                );
                $invocations[]  = array($exceptionEvent->getResponse(), $e, $singleRequest);
            }
        }

        if ($closeSession) {
            $session = $httpRequest->getSession();
            if ($session && $session->isStarted()) {
                $session->save();
            }
        }

        return $invocations;
    }

    /**
     * @param Request     $directRequest
     * @param HttpRequest $httpRequest
     * @return array
     */
    protected function getInvocationParameters(Request $directRequest, HttpRequest $httpRequest)
    {
        /** @var ServiceResolveEvent $beforeResolveEvent */
        $beforeResolveEvent = $this->dispatchEvent(
            RouterEvents::BEFORE_RESOLVE,
            new ServiceResolveEvent($directRequest, $httpRequest, null, array())
        );

        if (!$beforeResolveEvent->hasBeenResolved()) {
            $service   = $this->serviceResolver->getService($directRequest);
            $arguments = $this->serviceResolver->getArguments($directRequest, $httpRequest);
        } else {
            $service   = $beforeResolveEvent->getService();
            $arguments = $beforeResolveEvent->getArguments();
        }

        /** @var ServiceResolveEvent $afterResolveEvent */
        $afterResolveEvent = $this->dispatchEvent(
            RouterEvents::AFTER_RESOLVE,
            new ServiceResolveEvent($directRequest, $httpRequest, $service, $arguments)
        );

        return array($afterResolveEvent->getService(), $afterResolveEvent->getArguments());
    }

    /**
     * @param ServiceReference $service
     * @param array            $arguments
     * @param Request          $directRequest
     * @param HttpRequest      $httpRequest
     * @return mixed
     */
    protected function invokeService(
        ServiceReference $service,
        array $arguments,
        Request $directRequest,
        HttpRequest $httpRequest
    ) {
        /** @var InvokeEvent $beforeInvokeEvent */
        $beforeInvokeEvent = $this->dispatchEvent(
            RouterEvents::BEFORE_INVOKE,
            new InvokeEvent($directRequest, $httpRequest, $service, $arguments, null)
        );

        if (!$beforeInvokeEvent->isResultSet()) {
            $result = $service(array_values($arguments));
        } else {
            $result = $beforeInvokeEvent->getResult();
        }

        /** @var InvokeEvent $afterInvokeEvent */
        $afterInvokeEvent = $this->dispatchEvent(
            RouterEvents::AFTER_INVOKE,
            new InvokeEvent($directRequest, $httpRequest, $service, $arguments, $result)
        );

        return $afterInvokeEvent->getResult();
    }

    /**
     * @param string $eventName
     * @param Event  $event
     * @return Event
     */
    protected function dispatchEvent($eventName, Event $event)
    {
        if ($this->eventDispatcher) {
            return $this->eventDispatcher->dispatch($eventName, $event);
        } else {
            return $event;
        }
    }
}
