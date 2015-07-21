<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Exception;


/**
 * Class StrictArgumentValidationException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
class StrictArgumentValidationException extends \RuntimeException
{
    /**
     */
    public function __construct()
    {
        parent::__construct('Strict argument validation failed: not all parameters could be validated');
    }
}
