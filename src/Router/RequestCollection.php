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
class RequestCollection implements \IteratorAggregate, \Countable
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
        if (count($this->requests) !== 1) {
            return false;
        }

        /** @var Request $firstRequest */
        $firstRequest = reset($this->requests);
        return $firstRequest->isForm();
    }

    /**
     * @return boolean
     */
    public function isUpload()
    {
        if (count($this->requests) !== 1) {
            return false;
        }
        /** @var Request $firstRequest */
        $firstRequest = reset($this->requests);
        return $firstRequest->isUpload();
    }

    /**
     * @return boolean
     */
    public function isFormUpload()
    {
        return $this->isForm() && $this->isUpload();
    }
}
