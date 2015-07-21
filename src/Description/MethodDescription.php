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
 * Class MethodDescription
 *
 * @package TQ\ExtDirect\Description
 */
class MethodDescription implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $formHandler;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    private $namedParams;

    /**
     * @var bool
     */
    protected $strict;

    /**
     * @param string $name
     * @param bool   $formHandler
     * @param array  $params
     * @param bool   $namedParams
     * @param bool   $strict
     */
    public function __construct(
        $name,
        $formHandler = false,
        array $params = array(),
        $namedParams = false,
        $strict = true
    ) {
        $this->setName($name);
        $this->setFormHandler($formHandler);
        if (!$this->isFormHandler()) {
            $this->setParams($params);
            $this->setStrict($strict);
        }
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
     * @return boolean
     */
    public function isFormHandler()
    {
        return $this->formHandler;
    }

    /**
     * @param bool $formHandler
     * @return $this
     */
    public function setFormHandler($formHandler)
    {
        $this->formHandler = (bool)$formHandler;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = array();
        return $this->addParams($params);
    }

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params)
    {
        foreach ($params as $param) {
            $this->addParam($param);
        }
        return $this;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function addParam($param)
    {
        if ($this->isFormHandler()) {
            throw new \BadMethodCallException('Cannot add parameters to form handler methods');
        }
        $this->params[] = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasNamedParams()
    {
        return $this->namedParams;
    }

    /**
     * @param bool $namedParams
     * @return MethodDescription
     */
    public function setNamedParams($namedParams)
    {
        $this->namedParams = (bool)$namedParams;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * @param bool $strict
     * @return $this
     */
    public function setStrict($strict)
    {
        $this->strict = (bool)$strict;
        return $this;
    }

    /**
     * {@inheritdoc
     */
    public function jsonSerialize()
    {
        $description = array(
            'name' => $this->getName(),
        );

        if ($this->isFormHandler()) {
            $description['formHandler'] = true;
        } elseif ($this->hasNamedParams()) {
            $description['strict'] = $this->isStrict();
            $description['params'] = $this->getParams();
        } else {
            $description['len'] = count($this->getParams());
        }

        return $description;
    }
}
