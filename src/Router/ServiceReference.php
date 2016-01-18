<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\Validator\Constraint;
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
     * @return \ReflectionParameter[]
     */
    public function getParameters()
    {
        return $this->methodMetadata->parameters;
    }

    /**
     * @param string $name
     * @return \ReflectionParameter|null
     */
    public function getParameter($name)
    {
        if (isset($this->methodMetadata->parameters[$name])) {
            return $this->methodMetadata->parameters[$name];
        } else {
            return null;
        }
    }

    /**
     * @param string $name
     * @return Constraint[]
     */
    public function getParameterConstraints($name)
    {
        if (isset($this->methodMetadata->parameterMetadata[$name])) {
            return $this->methodMetadata->parameterMetadata[$name][0];
        } else {
            return array();
        }
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getParameterValidationGroups($name)
    {
        if (isset($this->methodMetadata->parameterMetadata[$name])) {
            return $this->methodMetadata->parameterMetadata[$name][1];
        } else {
            return null;
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isStrictParameterValidation($name)
    {
        if (isset($this->methodMetadata->parameterMetadata[$name])) {
            return $this->methodMetadata->parameterMetadata[$name][2];
        } else {
            return false;
        }
    }

    /**
     * @return array|null
     */
    public function getResultSerializationGroups()
    {
        if ($this->methodMetadata->result) {
            return $this->methodMetadata->result[0];
        } else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getResultSerializationAttributes()
    {
        if ($this->methodMetadata->result) {
            return $this->methodMetadata->result[1];
        } else {
            return null;
        }
    }

    /**
     * @return int|null
     */
    public function getResultSerializationVersion()
    {
        if ($this->methodMetadata->result !== null) {
            return $this->methodMetadata->result[2];
        } else {
            return null;
        }
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
