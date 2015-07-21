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
    public function convert($result)
    {
        if (is_object($result)) {
            return $this->serializer->toArray($result);
        }
        return $result;
    }
}
