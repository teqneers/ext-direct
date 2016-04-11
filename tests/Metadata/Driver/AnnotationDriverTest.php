<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.12.15
 * Time: 14:55
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Constraint;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Metadata\Driver\ClassAnnotationDriver;

/**
 * Class AnnotationDriverTest
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    protected function getDriver()
    {
        return new AnnotationDriver(new AnnotationReader());
    }

    public function testClassWithoutAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service1');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertNull($classMetadata);
    }

    public function testClassWithoutMethods()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertNull($classMetadata);
    }

    public function testClassRegularMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata', $classMetadata);

        $this->assertTrue($classMetadata->isAction);
        $this->assertEquals('app.direct.test', $classMetadata->serviceId);
        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);

        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodA'];
        $this->assertInstanceOf('TQ\ExtDirect\Metadata\MethodMetadata', $methodMetadata);

        $this->assertTrue($methodMetadata->isMethod);
        $this->assertFalse($methodMetadata->isFormHandler);
        $this->assertFalse($methodMetadata->hasNamedParams);
        $this->assertTrue($methodMetadata->isStrict);
        $this->assertEquals(array(), $methodMetadata->parameters);
        $this->assertEquals(array(), $methodMetadata->parameterMetadata);
    }

    public function testClassWithoutServiceId()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertEmpty($classMetadata->serviceId);
        $this->assertNull($classMetadata->serviceId);
    }

    public function testClassFormHandlerMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata', $classMetadata);

        $this->assertTrue($classMetadata->isAction);
        $this->assertNull($classMetadata->serviceId);
        $this->assertArrayHasKey('methodB', $classMetadata->methodMetadata);

        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodB'];
        $this->assertInstanceOf('TQ\ExtDirect\Metadata\MethodMetadata', $methodMetadata);

        $this->assertTrue($methodMetadata->isMethod);
        $this->assertTrue($methodMetadata->isFormHandler);
        $this->assertFalse($methodMetadata->hasNamedParams);
        $this->assertTrue($methodMetadata->isStrict);
        $this->assertEquals(array(), $methodMetadata->parameters);
        $this->assertEquals(array(), $methodMetadata->parameterMetadata);
    }

    public function testMethodParameterConstraints()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service5');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        foreach (array('methodA', 'methodB', 'methodC') as $m) {
            /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
            $methodMetadata = $classMetadata->methodMetadata[$m];

            /** @var Constraint[] $parameters */
            $constraints = $methodMetadata->parameterMetadata;
            $this->assertCount(1, $constraints);
            $this->assertArrayHasKey('a', $constraints);

            list($constraints, $validationGroup, $strict) = $constraints['a'];
            $this->assertCount(1, $constraints);

            /** @var Constraint $constraint */
            $constraint = current($constraints);
            $this->assertInstanceOf('Symfony\Component\Validator\Constraint', $constraint);
            $this->assertNull($validationGroup);

            $this->assertTrue($strict);
        }
    }

    public function testMethodParameterConstraintsAndValidationGroup()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service5');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        foreach (array('methodD', 'methodE', 'methodF', 'methodG', 'methodH') as $m) {
            /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
            $methodMetadata = $classMetadata->methodMetadata[$m];

            /** @var Constraint[] $parameters */
            $constraints = $methodMetadata->parameterMetadata;
            $this->assertCount(1, $constraints);
            $this->assertArrayHasKey('a', $constraints);

            list($constraints, $validationGroups, $strict) = $constraints['a'];
            $this->assertCount(1, $constraints);

            /** @var Constraint $constraint */
            $constraint = current($constraints);
            $this->assertInstanceOf('Symfony\Component\Validator\Constraint', $constraint);
            $this->assertEquals(['myGroup'], $validationGroups);

            $this->assertTrue($strict);
        }
    }

    public function testClassInheritance()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Sub\Service6');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);
        $this->assertArrayHasKey('methodB', $classMetadata->methodMetadata);
    }

    public function testClassWithMethodAnnotationOnNonPublicMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service7');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertNull($classMetadata);
    }

    public function testClassRegularStaticMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service8');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata', $classMetadata);

        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);

        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodA'];
        $this->assertInstanceOf('TQ\ExtDirect\Metadata\MethodMetadata', $methodMetadata);

        $this->assertTrue($methodMetadata->isMethod);
        $this->assertFalse($methodMetadata->isFormHandler);
        $this->assertFalse($methodMetadata->hasNamedParams);
        $this->assertTrue($methodMetadata->isStrict);
        $this->assertEquals(array(), $methodMetadata->parameters);
        $this->assertEquals(array(), $methodMetadata->parameterMetadata);
    }

    public function testClassWithStrictParameterAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service9');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);

        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodA'];

        /** @var Constraint[] $parameters */
        $constraints = $methodMetadata->parameterMetadata;
        $this->assertCount(1, $constraints);
        $this->assertArrayHasKey('a', $constraints);

        list($constraints, $validationGroups, $strict) = $constraints['a'];
        $this->assertCount(1, $constraints);
        $this->assertEmpty($validationGroups);
        $this->assertTrue($strict);
    }

    public function testMethodWithResultGroupsParameter()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service10');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodA'];

        list($groups, $attributes, $version) = $methodMetadata->result;
        $this->assertEquals(['a', 'b'], $groups);
        $this->assertEmpty($attributes);
        $this->assertNull($version);
    }

    public function testMethodWithResultAttributesParameter()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service10');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodB', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodB'];

        list($groups, $attributes, $version) = $methodMetadata->result;
        $this->assertEmpty($groups);
        $this->assertEquals(['a' => 1, 'b' => 2], $attributes);
        $this->assertNull($version);
    }

    public function testMethodWithResultVersionParameter()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service10');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodC', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodC'];

        list($groups, $attributes, $version) = $methodMetadata->result;
        $this->assertEmpty($groups);
        $this->assertEmpty($attributes);
        $this->assertEquals(1, $version);
    }

    public function testMethodWithResultAllParameters()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service10');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodD', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadata */
        $methodMetadata = $classMetadata->methodMetadata['methodD'];

        list($groups, $attributes, $version) = $methodMetadata->result;
        $this->assertEquals(['a', 'b'], $groups);
        $this->assertEquals(['a' => 1, 'b' => 2], $attributes);
        $this->assertEquals(1, $version);
    }

    public function testClassWithSecurityAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service11');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertEquals('true', $classMetadata->authorizationExpression);
    }

    public function tesMethodWithSecurityAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service11');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertArrayHasKey('methodA', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadataA */
        $methodMetadataA = $classMetadata->methodMetadata['methodA'];
        $this->assertNull($methodMetadataA->authorizationExpression);

        $this->assertArrayHasKey('methodB', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadataB */
        $methodMetadataB = $classMetadata->methodMetadata['methodB'];
        $this->assertEquals('true and false', $methodMetadataB->authorizationExpression);

        $this->assertArrayHasKey('methodC', $classMetadata->methodMetadata);
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $methodMetadataC */
        $methodMetadataC = $classMetadata->methodMetadata['methodC'];
        $this->assertEquals('true and true', $methodMetadataC->authorizationExpression);
    }
}
