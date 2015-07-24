<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:29
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Constraint;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;

/**
 * Class AnnotationDriverTest
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    protected function getDriver()
    {
        return new AnnotationDriver(new AnnotationReader(), array(__DIR__ . '/Services'));
    }

    public function testGetAllClassNames()
    {
        $driver  = $this->getDriver();
        $classes = $driver->getAllClassNames();
        sort($classes, SORT_NATURAL);
        $this->assertEquals(
            array(
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service5',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service7',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service8',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Sub\Service6'
            ),
            $classes
        );
    }

    public function testClassWithoutAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service1');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('Metadata\NullMetadata', $classMetadata);
    }

    public function testClassWithoutMethods()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('Metadata\NullMetadata', $classMetadata);
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
        $this->assertEquals(array(), $methodMetadata->constraints);
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
        $this->assertEquals(array(), $methodMetadata->constraints);
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
            $constraints = $methodMetadata->constraints;
            $this->assertCount(1, $constraints);
            $this->assertArrayHasKey('a', $constraints);
            $this->assertCount(1, $constraints['a']);

            /** @var Constraint $constraint */
            $constraint = current($constraints['a']);

            $this->assertInstanceOf('Symfony\Component\Validator\Constraint', $constraint);
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

        $this->assertInstanceOf('Metadata\NullMetadata', $classMetadata);
    }

    public function testClassRegularStaticMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3');
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
        $this->assertEquals(array(), $methodMetadata->constraints);
    }
}
