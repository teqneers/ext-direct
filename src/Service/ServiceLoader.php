<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 11.12.15
 * Time: 14:18
 */

namespace TQ\ExtDirect\Service;

/**
 * Interface ServiceLoader
 *
 * @package TQ\ExtDirect\Service
 */
interface ServiceLoader
{
    /**
     * @return array
     */
    public function load();
}
