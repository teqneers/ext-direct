<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

use TQ\ExtDirect\Metadata\ActionMetadata;

/**
 * Interface ServiceRegistry
 *
 * @package TQ\ExtDirect
 */
interface ServiceRegistry
{
    /**
     * @param string $class
     * @return ActionMetadata|null
     */
    public function getMetadataForClass($class);

    /**
     * @return array
     */
    public function getAllClassNames();
}
