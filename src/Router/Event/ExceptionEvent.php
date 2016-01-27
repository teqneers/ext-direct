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
use TQ\ExtDirect\Router\Response;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Router\ServiceReference;

/**
 * Class ExceptionEvent
 *
 * @package TQ\ExtDirect\Router\Event
 */
class ExceptionEvent extends AbstractRouterEvent
{
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ServiceReference|null
     */
    private $service;

    /**
     * @var array|null
     */
    private $arguments;

    /**
     * @param DirectRequest         $directRequest
     * @param HttpRequest           $httpRequest
     * @param \Exception            $exception
     * @param Response      $response
     * @param ServiceReference|null $service
     * @param array|null            $arguments
     */
    public function __construct(
        DirectRequest $directRequest,
        HttpRequest $httpRequest,
        \Exception $exception,
        Response $response,
        ServiceReference $service = null,
        array $arguments = null
    ) {
        parent::__construct($directRequest, $httpRequest);
        $this->exception = $exception;
        $this->response  = $response;
        $this->service   = $service;
        $this->arguments = $arguments;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return ServiceReference|null
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return array|null
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
