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
    protected $params = [];

    /**
     * @var bool
     */
    private $namedParams = false;

    /**
     * @var bool
     */
    protected $strict = true;

    /**
     * @var bool
     */
    protected $batched = true;

    /**
     * @param string    $name
     * @param bool      $formHandler
     * @param array     $params
     * @param bool      $namedParams
     * @param bool      $strict
     * @param bool|null $batched
     */
    public function __construct(
        $name,
        $formHandler = false,
        array $params = [],
        $namedParams = false,
        $strict = true,
        $batched = null
    ) {
        $this->setName($name);
        $this->setFormHandler($formHandler);
        if (!$this->isFormHandler()) {
            $this->setParams($params);
            $this->setNamedParams($namedParams);
            $this->setStrict($strict);
        }
        $this->setBatched($batched);
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
        $this->params = [];
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
        if ($this->isFormHandler()) {
            throw new \BadMethodCallException('Cannot set named params on form handler methods');
        }
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
        if ($this->isFormHandler()) {
            throw new \BadMethodCallException('Cannot set strict params on form handler methods');
        }
        $this->strict = (bool)$strict;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isBatched()
    {
        return $this->batched;
    }

    /**
     * @param bool|null $batched
     * @return $this
     */
    public function setBatched($batched)
    {
        $this->batched = is_bool($batched) ? $batched : null;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        $description = [
            'name' => $this->getName(),
        ];

        if ($this->isBatched() !== null) {
            $description['batched'] = $this->isBatched();
        }

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
