<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Router\Request as DirectRequest;


/**
 * Class ServiceResolver
 *
 * @package TQ\ExtDirect\Service
 */
interface ServiceResolverInterface
{
    /**
     * @param DirectRequest $directRequest
     * @return ServiceReference
     */
    public function getService(DirectRequest $directRequest);

    /**
     * @param DirectRequest $directRequest
     * @param HttpRequest   $httpRequest
     * @return array
     */
    public function getArguments(DirectRequest $directRequest, HttpRequest $httpRequest);
}
