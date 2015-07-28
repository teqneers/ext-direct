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
 * Class ResponseCollection
 *
 * @package TQ\ExtDirect
 */
class ResponseCollection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var AbstractResponse[]
     */
    private $responses;

    /**
     * @param AbstractResponse[] $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return AbstractResponse[]
     */
    public function all()
    {
        return $this->responses;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->responses);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->responses);
    }

    /**
     * @return AbstractResponse|null
     */
    public function getFirst()
    {
        if (count($this->responses) < 1) {
            return null;
        }
        return reset($this->responses);
    }

    /**
     * @param int $index
     * @return AbstractResponse|null
     */
    public function getAt($index)
    {
        if ($index >= count($this->responses) || $index < 0) {
            return null;
        }
        return $this->responses[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        if (count($this->responses) !== 1) {
            return $this->responses;
        } else {
            $singleResponse = reset($this->responses);
            return $singleResponse;
        }
    }
}
