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
     * @var string|null
     */
    public $serviceId;

    /**
     * @var string|null
     */
    public $alias;

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->isAction,
            $this->serviceId,
            $this->alias,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list($this->isAction, $this->serviceId, $this->alias, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
