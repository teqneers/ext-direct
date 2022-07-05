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
 * Class ServiceDescription
 *
 * @package TQ\ExtDirect\Service
 */
class ServiceDescription implements \JsonSerializable
{
    const TYPE_REMOTING = 'remoting';

    /**
     * @var string
     */
    protected $type = self::TYPE_REMOTING;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var ActionDescription[]
     */
    protected $actions = [];

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var bool|int|null
     */
    private $enableBuffer;

    /**
     * @var int|null
     */
    private $bufferLimit;

    /**
     * @var int|null
     */
    private $timeout;

    /**
     * @var int|null
     */
    private $maxRetries;

    /**
     * @param string           $url
     * @param string           $namespace
     * @param boolean|int|null $enableBuffer
     * @param int|null         $bufferLimit
     * @param int|null         $timeout
     * @param int|null         $maxRetries
     */
    public function __construct(
        $url,
        $namespace = 'Ext.global',
        $enableBuffer = null,
        $bufferLimit = null,
        $timeout = null,
        $maxRetries = null
    ) {
        $this->setUrl($url);
        $this->setNamespace($namespace);
        $this->setEnableBuffer($enableBuffer);
        $this->setBufferLimit($bufferLimit);
        $this->setTimeout($timeout);
        $this->setMaxRetries($maxRetries);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getEnableBuffer()
    {
        return $this->enableBuffer;
    }

    /**
     * @param bool|int|null $enableBuffer
     */
    public function setEnableBuffer($enableBuffer)
    {
        $this->enableBuffer = (is_int($enableBuffer) || is_bool($enableBuffer)) ? $enableBuffer : null;
    }

    /**
     * @return int|null
     */
    public function getBufferLimit()
    {
        return $this->bufferLimit;
    }

    /**
     * @param int|null $bufferLimit
     */
    public function setBufferLimit($bufferLimit)
    {
        $this->bufferLimit = is_int($bufferLimit) ? $bufferLimit : null;
    }

    /**
     * @return int|null
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int|null $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = is_int($timeout) ? $timeout : null;
    }

    /**
     * @return int|null
     */
    public function getMaxRetries()
    {
        return $this->maxRetries;
    }

    /**
     * @param int|null $maxRetries
     */
    public function setMaxRetries($maxRetries)
    {
        $this->maxRetries = is_int($maxRetries) ? $maxRetries : null;
    }

    /**
     * @return ActionDescription[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param ActionDescription[] $actions
     * @return $this
     */
    public function setActions(array $actions)
    {
        $this->actions = [];
        return $this->addActions($actions);
    }

    /**
     * @param ActionDescription[] $actions
     * @return $this
     */
    public function addActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAction($action);
        }
        return $this;
    }

    /**
     * @param ActionDescription $action
     * @return $this
     */
    public function addAction(ActionDescription $action)
    {
        $this->actions[] = $action;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $api = [
            'type'      => $this->getType(),
            'url'       => $this->getUrl(),
            'namespace' => $this->getNamespace(),
        ];

        if ($this->getEnableBuffer() !== null) {
            $api['enableBuffer'] = $this->getEnableBuffer();
        }
        if ($this->getBufferLimit() !== null) {
            $api['bufferLimit'] = $this->getBufferLimit();
        }
        if ($this->getTimeout() !== null) {
            $api['timeout'] = $this->getTimeout();
        }
        if ($this->getMaxRetries() !== null) {
            $api['maxRetries'] = $this->getMaxRetries();
        }

        $api['actions'] = [];
        foreach ($this->getActions() as $action) {
            $api['actions'][$action->getName()] = $action->getMethods();
        }

        return $api;
    }
}
