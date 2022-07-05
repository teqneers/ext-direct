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
 * Class Request
 *
 * @package TQ\ExtDirect
 */
class Request implements \JsonSerializable
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
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $isForm;

    /**
     * @var bool
     */
    private $isUpload;

    /**
     * @param int    $tid
     * @param string $action
     * @param string $method
     * @param array  $data
     * @param bool   $isForm
     * @param bool   $isUpload
     */
    public function __construct($tid, $action, $method, array $data, $isForm, $isUpload)
    {
        $this->tid      = $tid;
        $this->action   = $action;
        $this->method   = $method;
        $this->data     = $data;
        $this->isForm   = $isForm;
        $this->isUpload = $isUpload;
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
    public function getRequestKey()
    {
        return sprintf('%s:%s[%d]', $this->getAction(), $this->getMethod(), $this->getTid());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return boolean
     */
    public function isForm()
    {
        return $this->isForm;
    }

    /**
     * @return boolean
     */
    public function isUpload()
    {
        return $this->isUpload;
    }

    /**
     * @return boolean
     */
    public function isFormUpload()
    {
        return $this->isForm() && $this->isUpload();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'tid'          => $this->getTid(),
            'action'       => $this->getAction(),
            'method'       => $this->getMethod(),
            'data'         => $this->getData(),
            'isForm'       => $this->isForm(),
            'isUpload'     => $this->isUpload(),
            'isFormUpload' => $this->isFormUpload(),
        ];
    }
}
