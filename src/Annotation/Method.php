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
 * Class Method
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
class Method
{
    /**
     * @var bool
     */
    public $formHandler = false;

    /**
     * @var bool
     */
    public $namedParams = false;

    /**
     * @var bool
     */
    public $strict = true;

    /**
     * @var mixed
     */
    public $batched = null;

    /**
     * @var bool
     */
    public $session = true;
}
