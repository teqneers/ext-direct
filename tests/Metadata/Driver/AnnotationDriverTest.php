<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:29
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
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
        $driver = $this->getDriver();
        $this->assertEquals(
            array(
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4',
            ),
            $driver->getAllClassNames()
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

    public function testClassFormHandlerMethod()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4');
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $classMetadata */
        $classMetadata = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata', $classMetadata);

        $this->assertTrue($classMetadata->isAction);
        $this->assertEquals('app.direct.test', $classMetadata->serviceId);
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
}
