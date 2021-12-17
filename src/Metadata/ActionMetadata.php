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
     * @var string|null
     */
    public $authorizationExpression;

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize(array(
            $this->isAction,
            $this->serviceId,
            $this->alias,
            $this->authorizationExpression,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str): void
    {
        list(
            $this->isAction,
            $this->serviceId,
            $this->alias,
            $this->authorizationExpression,
            $parentStr
            ) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
