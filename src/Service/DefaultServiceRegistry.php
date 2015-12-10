<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use Metadata\AdvancedMetadataFactoryInterface;
use TQ\ExtDirect\Metadata\ActionMetadata;

/**
 * Class DefaultServiceRegistry
 *
 * @package TQ\ExtDirect
 */
class DefaultServiceRegistry implements ServiceRegistry
{
    /**
     * @var AdvancedMetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var NamingStrategy
     */
    private $namingStrategy;

    /**
     * @param AdvancedMetadataFactoryInterface $metadataFactory
     * @param NamingStrategy                   $namingStrategy
     */
    public function __construct(AdvancedMetadataFactoryInterface $metadataFactory, NamingStrategy $namingStrategy)
    {
        $this->metadataFactory = $metadataFactory;
        $this->namingStrategy  = $namingStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataForService($class)
    {
        $metadata = $this->metadataFactory->getMetadataForClass($class);
        if (!($metadata instanceof ActionMetadata) || !$metadata->isAction) {
            return null;
        }

        return $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata()
    {
        $all = array();
        foreach ($this->metadataFactory->getAllClassNames() as $className) {
            $all[] = $this->getMetadataForService($className);
        }

        return $all;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToActionName($className)
    {
        return $this->namingStrategy->convertToActionName($className);
    }


    /**
     * {@inheritdoc}
     */
    public function convertToClassName($actionName)
    {
        return $this->namingStrategy->convertToClassName($actionName);
    }
}
