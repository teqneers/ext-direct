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
 * Class ArgumentConverter
 *
 * @package TQ\ExtDirect\Router
 */
interface ArgumentConverterInterface
{
    /**
     * @param ServiceReference $service
     * @param array            $arguments
     * @return array
     */
    public function convert(ServiceReference $service, array $arguments);
}
