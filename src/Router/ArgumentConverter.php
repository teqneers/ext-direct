<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;

/**
 * Class ArgumentConverter
 *
 * @package TQ\ExtDirect\Router
 */
class ArgumentConverter implements ArgumentConverterInterface
{
    /**
     * @var ArrayTransformerInterface
     */
    private $serializer;

    /**
     * @param ArrayTransformerInterface $serializer
     */
    public function __construct(ArrayTransformerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(ServiceReference $service, array $arguments)
    {
        foreach ($arguments as $name => $value) {
            if (strpos($name, '__internal__') !== false) {
                continue;
            }

            $parameter = $service->getParameter($name);
            if (!$parameter || !is_array($value)) {
                continue;
            }

            $type = $parameter->getType();
            if (!$type || $type->isBuiltin()) {
                continue;
            }

            $arguments[$name] = $this->serializer->fromArray(
                $value,
                $type->getName(),
                $this->createDeserializationContext($service, $name)
            );
        }

        return $arguments;
    }

    /**
     * @param ServiceReference $service
     * @param string           $name
     * @return DeserializationContext
     */
    protected function createDeserializationContext(ServiceReference $service, $name)
    {
        $context    = DeserializationContext::create();
        $groups     = $service->getParameterSerializationGroups($name);
        $attributes = $service->getParameterSerializationAttributes($name);
        $version    = $service->getParameterSerializationVersion($name);
        if (!empty($groups)) {
            $context->setGroups($groups);
        }
        if (!empty($attributes)) {
            foreach ($attributes as $k => $v) {
                $context->setAttribute($k, $v);
            }
        }
        if ($version !== null) {
            $context->setVersion($version);
            return $context;
        }
        return $context;
    }
}
