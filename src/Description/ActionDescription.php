<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Description;

/**
 * Class ActionDescription
 *
 * @package TQ\ExtDirect\Description
 */
class ActionDescription
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var MethodDescription[]
     */
    protected $methods = array();

    /**
     * @param string              $name
     * @param MethodDescription[] $methods
     */
    public function __construct($name, array $methods = array())
    {
        $this->setName($name);
        $this->setMethods($methods);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return MethodDescription[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param MethodDescription[] $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = array();
        return $this->addMethods($methods);
    }

    /**
     * @param MethodDescription[] $methods
     * @return $this
     */
    public function addMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }
        return $this;
    }

    /**
     * @param MethodDescription $method
     * @return $this
     */
    public function addMethod(MethodDescription $method)
    {
        $this->methods[] = $method;
        return $this;
    }
}
