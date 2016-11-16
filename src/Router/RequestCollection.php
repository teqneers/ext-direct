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
 * Class RequestCollection
 *
 * @package TQ\ExtDirect
 */
class RequestCollection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var Request[]
     */
    private $requests;

    /**
     * @param Request[] $requests
     */
    public function __construct(array $requests)
    {
        $this->requests = $requests;
    }

    /**
     * @return Request[]
     */
    public function all()
    {
        return $this->requests;
    }

    /**
     * @return Request|null
     */
    public function getFirst()
    {
        if (count($this->requests) > 0) {
            return reset($this->requests);
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->requests);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->requests);
    }

    /**
     * @return boolean
     */
    public function isForm()
    {
        $firstRequest = $this->getFirst();
        if (!$firstRequest) {
            return false;
        }
        return $firstRequest->isForm();
    }

    /**
     * @return boolean
     */
    public function isUpload()
    {
        $firstRequest = $this->getFirst();
        if (!$firstRequest || !$firstRequest) {
            return false;
        }
        return $firstRequest->isUpload();
    }

    /**
     * @return boolean
     */
    public function isFormUpload()
    {
        return $this->isForm() && $this->isUpload();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        if (count($this->requests) !== 1) {
            return $this->requests;
        } else {
            return reset($this->requests);
        }
    }
}
