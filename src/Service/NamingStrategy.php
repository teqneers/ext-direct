<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

/**
 * Interface NamingStrategy
 *
 * @package TQ\ExtDirect\Service
 */
interface NamingStrategy
{
    /**
     * @param $className
     * @return string
     */
    public function convertToActionName($className);

    /**
     * @param $actionName
     * @return string
     */
    public function convertToClassName($actionName);

}
