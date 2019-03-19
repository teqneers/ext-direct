<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TQ\ExtDirect\Metadata\ActionMetadata;

/**
 * Class ContainerServiceFactory
 *
 * @package TQ\ExtDirect
 */
class ContainerServiceFactory implements ServiceFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function createService(ActionMetadata $metadata)
    {
        if (!$metadata->isAction) {
            throw new \InvalidArgumentException('Not a valid action');
        }
        if (!empty($metadata->serviceId)) {
            return $this->container->get($metadata->serviceId);
        }
        $reflection  = new \ReflectionClass($metadata->name);
        $constructor = $reflection->getConstructor();
        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            $service = $reflection->newInstance();
            if ($reflection->implementsInterface('Symfony\Component\DependencyInjection\ContainerAwareInterface')) {
                /** @var \Symfony\Component\DependencyInjection\ContainerAwareInterface $service */
                $service->setContainer($this->container);
            }
            return $service;
        }
        
        throw new \InvalidArgumentException('Cannot instantiate action');
    }
}
