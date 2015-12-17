<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Metadata;

use Metadata\MethodMetadata as BaseMethodMetadata;
use Symfony\Component\Validator\Constraint;

/**
 * Class MethodMetadata
 *
 * @package TQ\ExtDirect\Metadata
 */
class MethodMetadata extends BaseMethodMetadata
{
    /**
     * @var bool
     */
    public $isMethod = false;

    /**
     * @var bool
     */
    public $isFormHandler = false;

    /**
     * @var bool
     */
    public $hasNamedParams = false;

    /**
     * @var bool
     */
    public $isStrict = true;

    /**
     * @var \ReflectionParameter[]
     */
    public $parameters = [];

    /**
     * @var array
     */
    public $constraints = [];

    /**
     * @param \ReflectionParameter $parameter
     */
    public function addParameter(\ReflectionParameter $parameter)
    {
        $this->parameters[$parameter->name] = $parameter;
    }

    /**
     * @param \ReflectionParameter[] $parameters
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }
    }

    /**
     * @param string       $parameter
     * @param Constraint[] $constraints
     * @param array|null   $validationGroups
     */
    public function addParameterConstraints($parameter, array $constraints, array $validationGroups = null)
    {
        $this->constraints[$parameter] = [$constraints, $validationGroups];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $parameterNames = array_map(
            function (\ReflectionParameter $param) {
                return $param->name;
            },
            $this->parameters
        );

        return serialize(array(
            $this->isMethod,
            $this->isFormHandler,
            $this->hasNamedParams,
            $this->isStrict,
            $parameterNames,
            $this->constraints,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->isMethod,
            $this->isFormHandler,
            $this->hasNamedParams,
            $this->isStrict,
            $parameterNames,
            $this->constraints,
            $parentStr
            ) = unserialize($str);
        parent::unserialize($parentStr);

        foreach ($parameterNames as $parameterName) {
            $this->parameters[$parameterName] = new \ReflectionParameter(
                array(
                    $this->reflection
                        ->getDeclaringClass()
                        ->name,
                    $this->reflection
                        ->name
                ),
                $parameterName
            );
        }
    }
}
