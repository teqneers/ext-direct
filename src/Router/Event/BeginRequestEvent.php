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
use Symfony\Contracts\EventDispatcher\Event;
use TQ\ExtDirect\Router\RequestCollection;

/**
 * Class BeginRequestEvent
 *
 * @package TQ\ExtDirect\Router\Event
 */
class BeginRequestEvent extends Event
{
    /**
     * @var RequestCollection
     */
    private $directRequest;
    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @param RequestCollection $directRequest
     * @param HttpRequest $httpRequest
     */
    public function __construct(RequestCollection $directRequest, HttpRequest $httpRequest)
    {
        $this->directRequest = $directRequest;
        $this->httpRequest = $httpRequest;
    }

    /**
     * @return RequestCollection
     */
    public function getDirectRequest()
    {
        return $this->directRequest;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }
}
