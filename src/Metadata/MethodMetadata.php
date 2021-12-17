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
     * @var bool|null
     */
    public $batched = null;

    /**
     * @var bool
     */
    public $hasSession = true;

    /**
     * @var \ReflectionParameter[]
     */
    public $parameters = [];

    /**
     * @var array
     */
    public $parameterMetadata = [];

    /**
     * @var string|null
     */
    public $authorizationExpression;

    /**
     * @var array|null
     */
    public $result;

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
     * @param bool         $strict
     * @param array|null   $serializationGroups
     * @param array|null   $serializationAttributes
     * @param int|null     $serializationVersion
     */
    public function addParameterMetadata(
        $parameter,
        array $constraints,
        array $validationGroups = null,
        $strict = false,
        array $serializationGroups = null,
        array $serializationAttributes = null,
        $serializationVersion = null
    ) {
        $this->parameterMetadata[$parameter] = [
            $constraints,
            $validationGroups,
            (bool)$strict,
            $serializationGroups,
            $serializationAttributes,
            $serializationVersion,
        ];
    }

    /**
     * @param array    $groups
     * @param array    $attributes
     * @param int|null $version
     */
    public function setResult(array $groups, array $attributes, $version)
    {
        $this->result = [$groups, $attributes, $version];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        $parameterNames = array_map(
            function (\ReflectionParameter $param) {
                return $param->name;
            },
            $this->parameters
        );

        return serialize(
            [
                $this->isMethod,
                $this->isFormHandler,
                $this->hasNamedParams,
                $this->isStrict,
                $this->batched,
                $this->hasSession,
                $parameterNames,
                $this->parameterMetadata,
                $this->result,
                $this->authorizationExpression,
                parent::serialize(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str): void
    {
        list(
            $this->isMethod,
            $this->isFormHandler,
            $this->hasNamedParams,
            $this->isStrict,
            $this->batched,
            $this->hasSession,
            $parameterNames,
            $this->parameterMetadata,
            $this->result,
            $this->authorizationExpression,
            $parentStr
            ) = unserialize($str);
        parent::unserialize($parentStr);

        foreach ($parameterNames as $parameterName) {
            $this->parameters[$parameterName] = new \ReflectionParameter(
                [
                    $this->reflection
                        ->getDeclaringClass()
                        ->name,
                    $this->reflection
                        ->name,
                ],
                $parameterName
            );
        }
    }
}
