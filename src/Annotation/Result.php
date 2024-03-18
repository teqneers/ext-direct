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
 * Class Result
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Result
{
    public function __construct(
        /** @var string|array<string> */
        public string|array $groups = [],

        /** @var string|array<string> */
        public string|array $attributes = [],

        public ?int $version = null,
    ) {
        $arrayProperties = ['groups', 'attributes'];

        foreach ($arrayProperties as $property) {
            if (is_string($$property)) {
                $this->$property = [$$property];
            }
        }
    }
}
