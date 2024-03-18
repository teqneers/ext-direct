<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Annotation;

use Doctrine\Common\Annotations\NamedArgumentConstructor;

/**
 * Class Method
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Method
{
    public function __construct(
        public bool $formHandler = false,
        public bool $namedParams = false,
        public bool $strict = true,
        public mixed $batched = null,
        public bool $session = true,
    ) {}
}
