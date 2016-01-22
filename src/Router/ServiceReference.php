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
        return $this->getParameterMetadata($name, 0);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getParameterValidationGroups($name)
    {
        return $this->getParameterMetadata($name, 1);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isStrictParameterValidation($name)
    {
        return $this->getParameterMetadata($name, 2);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getParameterSerializationGroups($name)
    {
        return $this->getParameterMetadata($name, 3);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getParameterSerializationAttributes($name)
    {
        return $this->getParameterMetadata($name, 4);
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getParameterSerializationVersion($name)
    {
        return $this->getParameterMetadata($name, 5);
    }

    /**
     * @param string $name
     * @param int    $index
     * @return mixed|null
     */
    private function getParameterMetadata($name, $index)
    {
        if (isset($this->methodMetadata->parameterMetadata[$name][$index])) {
            return $this->methodMetadata->parameterMetadata[$name][$index];
        } else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getResultSerializationGroups()
    {
        return $this->getResultMetadata(0);
    }

    /**
     * @return array|null
     */
    public function getResultSerializationAttributes()
    {
        return $this->getResultMetadata(1);
    }

    /**
     * @return int|null
     */
    public function getResultSerializationVersion()
    {
        return $this->getResultMetadata(2);
    }

    /**
     * @param int $index
     * @return mixed|null
     */
    private function getResultMetadata($index)
    {
        if ($this->methodMetadata->result && isset($this->methodMetadata->result[$index])) {
            return $this->methodMetadata->result[$index];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getAuthorizationExpression()
    {
        if (!empty($this->methodMetadata->authorizationExpression)) {
            return $this->methodMetadata->authorizationExpression;
        } elseif (!empty($this->actionMetadata->authorizationExpression)) {
            return $this->actionMetadata->authorizationExpression;
        }
        return null;
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
