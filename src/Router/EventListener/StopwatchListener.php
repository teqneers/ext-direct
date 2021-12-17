<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use TQ\ExtDirect\Router\Event\InvokeEvent;
use TQ\ExtDirect\Router\Event\ServiceResolveEvent;
use TQ\ExtDirect\Router\Router;
use TQ\ExtDirect\Router\RouterEvents;

/**
 * Class StopwatchListener
 *
 * @package TQ\ExtDirect\Router\EventListener
 */
class StopwatchListener implements EventSubscriberInterface
{
    /**
     * @var Stopwatch|null
     */
    private $stopwatch;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            RouterEvents::BEGIN_REQUEST  => array('onBeginRequest', 255),
            RouterEvents::BEFORE_RESOLVE => array('onBeforeResolve', 255),
            RouterEvents::AFTER_RESOLVE  => array('onAfterResolve', -255),
            RouterEvents::BEFORE_INVOKE  => array('onBeforeInvoke', 255),
            RouterEvents::AFTER_INVOKE   => array('onAfterInvoke', -255),
            RouterEvents::END_REQUEST    => array('onEndRequest', -255),
        );
    }

    /**
     * @param Stopwatch|null $stopwatch
     */
    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     */
    public function onBeginRequest()
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->start(Router::class);
    }

    /**
     * @param ServiceResolveEvent $event
     */
    public function onBeforeResolve(ServiceResolveEvent $event)
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->start('resolve ' . $event->getDirectRequest()
                                                   ->getRequestKey());
    }

    /**
     * @param ServiceResolveEvent $event
     */
    public function onAfterResolve(ServiceResolveEvent $event)
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->stop('resolve ' . $event->getDirectRequest()
                                                  ->getRequestKey());
    }

    /**
     * @param InvokeEvent $event
     */
    public function onBeforeInvoke(InvokeEvent $event)
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->start('invoke ' . $event->getDirectRequest()
                                                  ->getRequestKey());
    }

    /**
     * @param InvokeEvent $event
     */
    public function onAfterInvoke(InvokeEvent $event)
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->stop('invoke ' . $event->getDirectRequest()
                                                 ->getRequestKey());
    }

    /**
     */
    public function onEndRequest()
    {
        if (!$this->stopwatch) {
            return;
        }
        $this->stopwatch->stop(Router::class);
    }
}
