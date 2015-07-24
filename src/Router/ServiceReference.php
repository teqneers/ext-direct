<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use TQ\ExtDirect\Metadata\ActionMetadata;
use TQ\ExtDirect\Metadata\MethodMetadata;

/**
 * Class ServiceReference
 *
 * @package TQ\ExtDirect\Router
 */
class ServiceReference
{
    /**
     * @var object|string
     */
    private $service;

    /**
     * @var ActionMetadata
     */
    private $actionMetadata;

    /**
     * @var MethodMetadata
     */
    private $methodMetadata;

    /**
     * @param object|string  $service
     * @param ActionMetadata $actionMetadata
     * @param MethodMetadata $methodMetadata
     */
    public function __construct($service, ActionMetadata $actionMetadata, MethodMetadata $methodMetadata)
    {
        $this->service        = $service;
        $this->actionMetadata = $actionMetadata;
        $this->methodMetadata = $methodMetadata;
    }

    /**
     * @return object|string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return ActionMetadata
     */
    public function getActionMetadata()
    {
        return $this->actionMetadata;
    }

    /**
     * @return MethodMetadata
     */
    public function getMethodMetadata()
    {
        return $this->methodMetadata;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return array($this->service, $this->methodMetadata->name);
    }

    /**
     * @param array $arguments
     * @return mixed
     */
    public function __invoke(array $arguments = array())
    {
        return call_user_func_array($this->getCallable(), $arguments);
    }
}
