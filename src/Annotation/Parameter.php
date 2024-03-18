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
 * Class Parameter
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Parameter
{
    public function __construct(
        public string $name,

        /** @var string|array<Symfony\Component\Validator\Constraint> */
        public string|array $constraints = [],

        /** @var string|null|array<string> */
        public string|null|array $validationGroups = null,

        public bool $strict = true,

        /** @var string|array<string> */
        public string|array $serializationGroups = [],

        /** @var string|array<string> */
        public string|array $serializationAttributes = [],

        public ?int $serializationVersion = null,
    ) {
        $arrayProperties = [
            'constraints', 'validationGroups', 'serializationGroups',
            'serializationAttributes'
        ];

        foreach ($arrayProperties as $property) {
            if (is_string($$property)) {
                $this->$property = [$$property];
            }
        }
    }
}
