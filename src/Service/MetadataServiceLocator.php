<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use Metadata\MetadataFactory;

/**
 * Class ServiceLocator
 *
 * @package TQ\ExtDirect
 */
class MetadataServiceLocator implements ServiceLocator
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @param MetadataFactory $metadataFactory
     */
    public function __construct(MetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataForClass($class)
    {
        return $this->metadataFactory->getMetadataForClass($class);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return $this->metadataFactory->getAllClassNames();
    }
}
