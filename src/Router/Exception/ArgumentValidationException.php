<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use TQ\ExtDirect\Router\ArgumentValidationResult;


/**
 * Class ArgumentValidationException
 *
 * @package TQ\ExtDirect\Router\Exception
 */
class ArgumentValidationException extends \InvalidArgumentException
{
    /**
     * @var ArgumentValidationResult
     */
    private $result;

    /**
     * @param ArgumentValidationResult $result
     */
    public function __construct(ArgumentValidationResult $result)
    {
        $this->result   = $result;
        $violationsInfo = array();
        foreach ($this->getViolations() as $parameter => $violation) {
            /** @var ConstraintViolationInterface $violation */

            if ($violation->getPropertyPath() !== '') {
                $key = $parameter . '/' . $violation->getPropertyPath();
            } else {
                $key = $parameter;
            }
            $violationsInfo[$key][] = sprintf('%s [%s]', $violation->getMessage(), $violation->getCode());
        }

        parent::__construct('Argument validation failed: ' . json_encode($violationsInfo));
    }

    /**
     * @return \Generator
     */
    public function getViolations()
    {
        foreach ($this->result as $parameter => $violations) {
            /** @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                yield $parameter => $violation;
            }
        }
    }

    /**
     * @return ArgumentValidationResult
     */
    public function getResult()
    {
        return $this->result;
    }
}
