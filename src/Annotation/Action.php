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
 * Class Action
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Action
{
    public function __construct(
        public ?string $serviceId = null,
        public ?string $alias = null,
    ) {}
}
