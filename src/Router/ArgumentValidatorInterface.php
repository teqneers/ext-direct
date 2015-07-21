<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use TQ\ExtDirect\Router\Exception\ArgumentValidationException;
use TQ\ExtDirect\Router\Exception\StrictArgumentValidationException;


/**
 * Class ArgumentValidator
 *
 * @package TQ\ExtDirect\Router
 */
interface ArgumentValidatorInterface
{
    /**
     * @param ServiceReference $service
     * @param array            $arguments
     * @throws StrictArgumentValidationException
     * @throws ArgumentValidationException
     */
    public function validate(ServiceReference $service, array $arguments);
}
