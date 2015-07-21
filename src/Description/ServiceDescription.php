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
    protected $actions = array();

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param string $url
     * @param string $namespace
     */
    public function __construct($url, $namespace = 'Ext.global')
    {
        $this->setUrl($url);
        $this->setNamespace($namespace);
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
        $this->actions = array();
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

    /**
     * {@inheritdoc
     */
    public function jsonSerialize()
    {
        $actions = array();
        foreach ($this->getActions() as $action) {
            $actions[$action->getName()] = $action->getMethods();
        }

        return array(
            'type'      => $this->getType(),
            'url'       => $this->getUrl(),
            'namespace' => $this->getNamespace(),
            'actions'   => $actions
        );
    }
}
