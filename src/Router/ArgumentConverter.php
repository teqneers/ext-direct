<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use JMS\Serializer\DeserializationContext;
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
        foreach ($arguments as $name => $value) {
            if (strpos($name, '__internal__') !== false) {
                continue;
            }

            $parameter = $service->getParameter($name);
            if (!$parameter || !is_array($value)) {
                continue;
            }

            if (!$parameter->getClass()) {
                continue;
            }

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
            }

            $arguments[$name] = $this->serializer->fromArray(
                $value, $parameter->getClass()->name, $context
            );
        }

        return $arguments;
    }
}
