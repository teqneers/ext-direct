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
     * @var Response[]
     */
    private $responses;

    /**
     * @param Response[] $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return Response[]
     */
    public function all()
    {
        return $this->responses;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->responses);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->responses);
    }

    /**
     * @return Response|null
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
     * @return Response|null
     */
    public function getAt($index)
    {
        if ($index >= count($this->responses) || $index < 0) {
            return null;
        }
        return $this->responses[$index];
    }

    public function jsonSerialize(): mixed
    {
        if (count($this->responses) !== 1) {
            return $this->responses;
        }

        return reset($this->responses);
    }
}
