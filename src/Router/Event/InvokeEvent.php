<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Event;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Router\ServiceReference;

/**
 * Class InvokeEvent
 *
 * @package TQ\ExtDirect\Router\Event
 */
class InvokeEvent extends AbstractRouterEvent
{
    /**
     * @var ServiceReference
     */
    private $service;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var bool
     */
    private $resultSet = false;

    /**
     * @param DirectRequest    $directRequest
     * @param HttpRequest      $httpRequest
     * @param ServiceReference $service
     * @param array            $arguments
     * @param mixed            $result
     */
    public function __construct(
        DirectRequest $directRequest,
        HttpRequest $httpRequest,
        ServiceReference $service,
        array $arguments,
        $result = null
    ) {
        parent::__construct($directRequest, $httpRequest);
        $this->service   = $service;
        $this->arguments = $arguments;
        $this->result    = $result;
    }

    /**
     * @return ServiceReference
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->resultSet = true;
        $this->result    = $result;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isResultSet()
    {
        return $this->resultSet;
    }
}
