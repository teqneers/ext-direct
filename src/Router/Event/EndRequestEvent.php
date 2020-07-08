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
use TQ\ExtDirect\Router\RequestCollection;
use TQ\ExtDirect\Router\ResponseCollection;

/**
 * Class EndRequestEvent
 *
 * @package TQ\ExtDirect\Router\Event
 */
class EndRequestEvent extends BeginRequestEvent
{
    /**
     * @var ResponseCollection
     */
    private $directResponse;

    /**
     * @param RequestCollection $directRequest
     * @param ResponseCollection $directResponse
     * @param HttpRequest $httpRequest
     */
    public function __construct(
        RequestCollection $directRequest,
        ResponseCollection $directResponse,
        HttpRequest $httpRequest
    ) {
        parent::__construct($directRequest, $httpRequest);
        $this->directResponse = $directResponse;
    }

    /**
     * @return ResponseCollection
     */
    public function getDirectResponse()
    {
        return $this->directResponse;
    }

    /**
     * @param ResponseCollection $directResponse
     * @return $this
     */
    public function setDirectResponse($directResponse)
    {
        $this->directResponse = $directResponse;
        return $this;
    }
}
