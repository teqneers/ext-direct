<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * Class ResultConverter
 *
 * @package TQ\ExtDirect\Router
 */
class ResultConverter implements ResultConverterInterface
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
    public function convert(ServiceReference $service, $result)
    {
        if (is_object($result) || is_array($result)) {

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

            return $this->serializer->toArray($result, $context);
        }
        return $result;
    }
}
