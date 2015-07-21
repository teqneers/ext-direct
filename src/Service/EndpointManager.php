<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Service;

/**
 * Class ServiceManager
 *
 * @package TQ\ExtDirect\Service
 */
class EndpointManager
{
    /**
     * @var Endpoint[]
     */
    private $services = array();

    /**
     * @param Endpoint $endpoint
     * @return $this
     */
    public function addEndpoint(Endpoint $endpoint)
    {
        $this->services[$endpoint->getId()] = $endpoint;
        return $this;
    }

    /**
     * @param string $id
     * @return Endpoint
     */
    public function getEndpoint($id)
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }
        throw new \InvalidArgumentException('Endpoint "' . $id . '" not found"');
    }
}
