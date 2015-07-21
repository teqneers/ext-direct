<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Annotation;


/**
 * Class Action
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Action
{
    /**
     * @var string
     */
    public $serviceId;
}
