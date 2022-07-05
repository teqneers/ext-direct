<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

/**
 * Class Response
 *
 * @package TQ\ExtDirect
 */
class Response implements \JsonSerializable
{
    const TYPE_RPC       = 'rpc';
    const TYPE_EXCEPTION = 'exception';
    const TYPE_EVENT     = 'event';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type' => $this->getType(),
        ];
    }
}
