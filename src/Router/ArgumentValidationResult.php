<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 16.12.15
 * Time: 13:55
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Traversable;

/**
 * Class ArgumentValidationResult
 *
 * @package TQ\ExtDirect\Router
 */
class ArgumentValidationResult implements \Countable, \IteratorAggregate
{
    /**
     * @var ConstraintViolationListInterface[]
     */
    private $results;

    /**
     * @param ConstraintViolationListInterface[] $results
     */
    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

    /**
     * @return bool
     */
    public function hasViolations()
    {
        return count($this->results) > 0;
    }

    /**
     * @param string $argumentName
     * @return bool
     */
    public function hasViolationsFor($argumentName)
    {
        $result = $this->getViolationsFor($argumentName);
        return $result ? count($result) > 0 : false;
    }


    /**
     * @param string $argumentName
     * @return ConstraintViolationListInterface|null
     */
    public function getViolationsFor($argumentName)
    {
        if (!isset($this->results[$argumentName])) {
            return null;
        }
        return $this->results[$argumentName];
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->results);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        foreach ($this->results as $argumentName => $result) {
            yield $argumentName => $result;
        }
    }
}
