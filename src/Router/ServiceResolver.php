<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Metadata\ActionMetadata;
use TQ\ExtDirect\Metadata\MethodMetadata;
use TQ\ExtDirect\Router\Exception\ActionNotFoundException;
use TQ\ExtDirect\Router\Exception\MethodNotFoundException;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Service\ServiceFactory;
use TQ\ExtDirect\Service\ServiceRegistry;

/**
 * Class ServiceResolver
 *
 * @package TQ\ExtDirect\Service
 */
class ServiceResolver implements ServiceResolverInterface
{
    /**
     * @var ServiceRegistry
     */
    private $serviceRegistry;

    /**
     * @var ServiceFactory
     */
    private $serviceFactory;

    /**
     * @param ServiceRegistry $serviceRegistry
     * @param ServiceFactory  $serviceFactory
     */
    public function __construct(ServiceRegistry $serviceRegistry, ServiceFactory $serviceFactory)
    {
        $this->serviceRegistry = $serviceRegistry;
        $this->serviceFactory  = $serviceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getService(DirectRequest $directRequest)
    {
        /** @var ActionMetadata $actionMetadata */
        /** @var MethodMetadata $methodMetadata */
        list($actionMetadata, $methodMetadata) = $this->assertMetadata($directRequest);

        if ($methodMetadata->reflection->isStatic()) {
            $service = $actionMetadata->name;
        } else {
            $service = $this->serviceFactory->createService($actionMetadata);
        }

        return new ServiceReference(
            $service,
            $actionMetadata,
            $methodMetadata
        );
    }

    /**
     * @param DirectRequest $directRequest
     * @return array
     * @throws ActionNotFoundException
     * @throws MethodNotFoundException
     */
    protected function assertMetadata(DirectRequest $directRequest)
    {
        $actionMetadata = $this->serviceRegistry->getService($directRequest->getAction());
        if (!$actionMetadata) {
            throw new ActionNotFoundException($directRequest);
        }

        if (!isset($actionMetadata->methodMetadata[$directRequest->getMethod()])) {
            throw new MethodNotFoundException($directRequest);
        }

        return array(
            $actionMetadata,
            $actionMetadata->methodMetadata[$directRequest->getMethod()]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(DirectRequest $directRequest, HttpRequest $httpRequest)
    {
        /** @var MethodMetadata $methodMetadata */
        list(, $methodMetadata) = $this->assertMetadata($directRequest);

        $requestParameters = $directRequest->getData();
        $arguments         = array();
        $i                 = 0;
        foreach ($methodMetadata->parameters as $parameter) {
            $type = $parameter->getType();
            if ($type && $type->getName() === HttpRequest::class) {
                $arguments['__internal__http_request__'] = $httpRequest;
            } elseif ($type && $type->getName() === DirectRequest::class) {
                $arguments['__internal__direct_request__'] = $directRequest;
            } elseif ($type && $type->getName() === ArgumentValidationResult::class) {
                $arguments['__internal__validation_result__'] = new ArgumentValidationResult();
            } else {
                if (isset($requestParameters[$i])) {
                    $arguments[$parameter->name] = $requestParameters[$i];
                } else {
                    $arguments[$parameter->name] = null;
                }
                $i++;
            }
        }
        return $arguments;
    }
}
