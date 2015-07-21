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
 * Interface ServiceFactory
 *
 * @package TQ\ExtDirect
 */
interface ServiceFactory
{
    /**
     * @param ActionMetadata $metadata
     * @return object
     */
    public function createService(ActionMetadata $metadata);
}
