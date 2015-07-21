<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

/**
 * Interface ResultConverterInterface
 *
 * @package TQ\ExtDirect\Router
 */
interface ResultConverterInterface
{
    /**
     * @param mixed $result
     * @return mixed
     */
    public function convert($result);
}
