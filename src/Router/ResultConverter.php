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
use JMS\Serializer\SerializationContext;

/**
 * Class ResultConverter
 *
 * @package TQ\ExtDirect\Router
 */
class ResultConverter implements ResultConverterInterface
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
    public function convert(ServiceReference $service, $result)
    {
        if (is_callable($result)) {
            return $result($this->serializer, $this->createSerializationContext($service));
        } elseif (is_object($result) || is_array($result)) {
            return $this->serializer->toArray($result, $this->createSerializationContext($service));
        } else {
            return $result;
        }
    }

    /**
     * @param ServiceReference $service
     * @return SerializationContext
     */
    protected function createSerializationContext(ServiceReference $service)
    {
        $context    = SerializationContext::create();
        $groups     = $service->getResultSerializationGroups();
        $attributes = $service->getResultSerializationAttributes();
        $version    = $service->getResultSerializationVersion();
        if (!empty($groups)) {
            $context->setGroups($groups);
        }
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $context->setAttribute($key, $value);
            }
        }
        if ($version !== null) {
            $context->setVersion($version);
        }
        $context->setSerializeNull(true);
        $context->enableMaxDepthChecks();
        return $context;
    }
}
