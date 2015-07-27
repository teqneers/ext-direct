<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

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
     * @var MethodMetadata
     */
    private $methodMetadata;

    /**
     * @param object|string  $service
     * @param MethodMetadata $methodMetadata
     */
    public function __construct($service, MethodMetadata $methodMetadata)
    {
        $this->service        = $service;
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
