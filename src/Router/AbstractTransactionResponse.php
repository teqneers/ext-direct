<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

/**
 * Class AbstractTransactionResponse
 *
 * @package TQ\ExtDirect
 */
abstract class AbstractTransactionResponse extends Response
{
    /**
     * @var int
     */
    private $tid;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $method;

    /**
     * Constructor
     *
     * @param string  $type
     * @param integer $tid
     * @param string  $action
     * @param string  $method
     */
    public function __construct($type, $tid, $action, $method)
    {
        parent::__construct($type);
        $this->tid    = $tid;
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * @return int
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                'tid'    => $this->getTid(),
                'action' => $this->getAction(),
                'method' => $this->getMethod(),
            )
        );
    }
}
