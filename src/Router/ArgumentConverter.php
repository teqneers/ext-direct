<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use JMS\Serializer\Serializer;

/**
 * Class ArgumentConverter
 *
 * @package TQ\ExtDirect\Router
 */
class ArgumentConverter implements ArgumentConverterInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(ServiceReference $service, array $arguments)
    {
        $methodMetadata = $service->getMethodMetadata();
        foreach ($arguments as $name => $value) {
            if (strpos($name, '__internal__') !== false) {
                continue;
            }

            if (!isset($methodMetadata->parameters[$name])
                || !is_array($value)
            ) {
                continue;
            }

            $parameter = $methodMetadata->parameters[$name];
            if (!$parameter->getClass()) {
                continue;
            }

            $arguments[$name] = $this->serializer->fromArray(
                $value, $parameter->getClass()
                                  ->getName()
            );
        }

        return $arguments;
    }
}
