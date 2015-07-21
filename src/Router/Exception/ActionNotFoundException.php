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
 * Class ActionNotFoundException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
class ActionNotFoundException extends AbstractResolverException
{
    /**
     * @param DirectRequest $request
     * @param \Exception    $previous
     */
    public function __construct(DirectRequest $request, \Exception $previous = null)
    {
        parent::__construct($request, sprintf('The action "%s" cannot be found', $request->getAction()), $previous);
    }
}
