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
 * Class DefaultNamingStrategy
 *
 * @package TQ\ExtDirect\Service
 */
class DefaultNamingStrategy implements NamingStrategy
{

    /**
     * {@inheritdoc}
     */
    public function convertToActionName($className)
    {
        return str_replace('\\', '.', $className);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToClassName($actionName)
    {
        return str_replace('.', '\\', $actionName);
    }
}
