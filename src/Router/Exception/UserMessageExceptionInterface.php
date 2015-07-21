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
 * Interface UserMessageExceptionInterface
 *
 * @package TQ\ExtDirect\Router\Exception
 */
interface UserMessageExceptionInterface
{
    /**
     * @return string
     */
    public function getUserMessage();
}
