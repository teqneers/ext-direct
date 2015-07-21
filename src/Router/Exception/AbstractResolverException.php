<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Exception;

use TQ\ExtDirect\Router\Request as DirectRequest;

/**
 * Class AbstractResolverException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
abstract class AbstractResolverException extends \RuntimeException
{
    /**
     * @var DirectRequest
     */
    private $request;

    /**
     * @param DirectRequest $request
     * @param string        $message
     * @param \Exception    $previous
     */
    public function __construct(DirectRequest $request, $message, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->request = $request;
    }

    /**
     * @return DirectRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}
