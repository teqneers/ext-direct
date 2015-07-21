<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use TQ\ExtDirect\Router\Exception\ArgumentValidationException;
use TQ\ExtDirect\Router\Exception\StrictArgumentValidationException;

/**
 * Class ArgumentValidator
 *
 * @package TQ\ExtDirect\Router
 */
class ArgumentValidator implements ArgumentValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var bool
     */
    private $strict = true;

    /**
     * @param ValidatorInterface $validator
     * @param bool               $strict
     */
    public function __construct(ValidatorInterface $validator, $strict = true)
    {
        $this->setValidator($validator);
        $this->setStrict($strict);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ServiceReference $service, array $arguments)
    {
        $methodMetadata   = $service->getMethodMetadata();
        $validationResult = array();
        $parameterCount   = 0;
        $validatedCount   = 0;
        foreach ($arguments as $name => $value) {
            if (strpos($name, '__internal__') !== false) {
                continue;
            }
            if (isset($methodMetadata->constraints[$name])
                && !empty($methodMetadata->constraints[$name])
            ) {
                $violations = $this->validator->validate($value, $methodMetadata->constraints[$name]);
                if (count($violations)) {
                    $validationResult[$name] = $violations;
                }
                $validatedCount++;
            }
            $parameterCount++;
        }

        if ($this->strict && ($parameterCount !== $validatedCount)) {
            throw new StrictArgumentValidationException();
        }

        if (!empty($validationResult)) {
            throw new ArgumentValidationException($validationResult);
        }
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * @param bool $strict
     * @return $this
     */
    public function setStrict($strict)
    {
        $this->strict = (bool)$strict;
        return $this;
    }
}
