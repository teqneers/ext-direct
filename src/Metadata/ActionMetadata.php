<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * Class ActionMetadata
 *
 * @package TQ\ExtDirect\Metadata
 */
class ActionMetadata extends MergeableClassMetadata
{
    /**
     * @var bool
     */
    public $isAction = false;

    /**
     * @var string
     */
    public $serviceId;

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->isAction,
            $this->serviceId,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list($this->isAction, $this->serviceId, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
