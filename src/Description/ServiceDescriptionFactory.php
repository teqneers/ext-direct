<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Description;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Metadata\MethodMetadata;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Service\NamingStrategy;
use TQ\ExtDirect\Service\ServiceRegistry;

/**
 * Class ServiceDescriptionFactory
 *
 * @package TQ\ExtDirect\Service
 */
class ServiceDescriptionFactory
{
    /**
     * @var ServiceRegistry
     */
    private $serviceRegistry;

    /**
     * @var NamingStrategy
     */
    private $namingStrategy;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @param ServiceRegistry $serviceRegistry
     * @param NamingStrategy  $namingStrategy
     * @param string          $namespace
     */
    public function __construct(ServiceRegistry $serviceRegistry, NamingStrategy $namingStrategy, $namespace)
    {
        $this->serviceRegistry = $serviceRegistry;
        $this->namingStrategy  = $namingStrategy;
        $this->namespace       = $namespace;
    }

    /**
     * @param string $url
     * @return ServiceDescription
     */
    public function createServiceDescription($url)
    {
        $serviceDescription = new ServiceDescription($url, $this->namespace);

        foreach ($this->serviceRegistry->getAllMetadata() as $actionMetadata) {
            if (!$actionMetadata) {
                continue;
            }
            $actionName = $this->namingStrategy->convertToActionName($actionMetadata->name);

            $actionDescription = new ActionDescription($actionName);
            foreach ($actionMetadata->methodMetadata as $methodMetadata) {
                /** @var MethodMetadata $methodMetadata */

                if (!$methodMetadata->isMethod) {
                    continue;
                }

                $parameters = array();
                foreach ($methodMetadata->parameters as $parameter) {
                    if (($class = $parameter->getClass()) === null
                        || (
                            $class->name !== HttpRequest::class
                            && $class->name !== DirectRequest::class
                        )
                    ) {
                        $parameters[] = $parameter->name;
                    }
                }

                $actionDescription->addMethod(new MethodDescription(
                    $methodMetadata->name,
                    $methodMetadata->isFormHandler,
                    $parameters,
                    $methodMetadata->hasNamedParams,
                    $methodMetadata->isStrict
                ));
            }

            if (count($actionDescription->getMethods())) {
                $serviceDescription->addAction($actionDescription);
            }
        }

        return $serviceDescription;
    }
}
