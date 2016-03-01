<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use Metadata\MetadataFactoryInterface;
use TQ\ExtDirect\Metadata\ActionMetadata;

/**
 * Class DefaultServiceRegistry
 *
 * @package TQ\ExtDirect
 */
class DefaultServiceRegistry implements ServiceRegistry
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var NamingStrategy
     */
    private $namingStrategy;

    /**
     * @var array
     */
    private $services = array();

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param NamingStrategy           $namingStrategy
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, NamingStrategy $namingStrategy)
    {
        $this->metadataFactory = $metadataFactory;
        $this->namingStrategy  = $namingStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getService($service)
    {
        $serviceConfig = $this->findServiceConfig($service);
        if (!$serviceConfig) {
            return null;
        }
        list($class, $serviceId, $alias) = $serviceConfig;

        $metadata = $this->metadataFactory->getMetadataForClass($class);
        if (!($metadata instanceof ActionMetadata) || !$metadata->isAction) {
            return null;
        }

        if ($serviceId) {
            $metadata->serviceId = $serviceId;
        }
        if ($alias) {
            $metadata->alias = $alias;
        } elseif (!$metadata->alias) {
            $metadata->alias = $service;
        }

        return $metadata;
    }

    /**
     * @param string $service
     * @return array|null
     */
    private function findServiceConfig($service)
    {
        if (!isset($this->services[$service])) {
            foreach ($this->services as $sc) {
                if ($sc[2] === $service) {
                    return $sc;
                }
            }
            foreach ($this->services as $sc) {
                $metadata = $this->metadataFactory->getMetadataForClass($sc[0]);
                if ($metadata instanceof ActionMetadata && $metadata->isAction && $metadata->alias === $service) {
                    return $sc;
                }
            }
        } else {
            return $this->services[$service];
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllServices()
    {
        $all = array();
        foreach (array_keys($this->services) as $service) {
            $all[] = $this->getService($service);
        }

        return $all;
    }

    /**
     * @param array $services
     * @return $this
     */
    public function addServices(array $services)
    {
        foreach ($services as $key => $value) {
            if (is_numeric($key)) {
                $class     = $value;
                $serviceId = null;
                $alias     = null;
            } else {
                $class = $key;
                if (is_array($value)) {
                    list($serviceId, $alias) = $value;
                } else {
                    $serviceId = $value;
                    $alias     = null;
                }
            }
            $this->addService($class, $serviceId, $alias);
        }

        return $this;
    }

    /**
     * @param string      $class
     * @param string|null $serviceId
     * @param string|null $alias
     * @return $this
     */
    public function addService($class, $serviceId = null, $alias = null)
    {
        if (!$alias) {
            $key = $this->namingStrategy->convertToActionName($class);
        } else {
            $key = $alias;
        }

        $this->services[$key] = [$class, $serviceId, $alias];
        return $this;
    }

    /**
     * @param ServiceLoader $serviceLoader
     */
    public function importServices(ServiceLoader $serviceLoader)
    {
        $this->addServices($serviceLoader->load());
    }
}
