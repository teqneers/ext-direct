<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:20
 */

namespace TQ\ExtDirect\Tests\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Metadata\Driver\PathAnnotationDriver;
use TQ\ExtDirect\Service\ContainerServiceFactory;

/**
 * Class ContainerServiceFactoryTest
 *
 * @package TQ\ExtDirect\Tests\Service
 */
class ContainerServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testServiceFactoryWithServiceId()
    {
        $classMetadata = $this->loadMetadataForClass('TQ\ExtDirect\Tests\Service\Services\Service1');
        $container     = $this->createContainer();

        $service = new \TQ\ExtDirect\Tests\Service\Services\Service1();

        $container->expects($this->once())
                  ->method('get')
                  ->with($this->equalTo('app.direct.test'))
                  ->willReturn($service);

        $factory = new ContainerServiceFactory($container);

        $this->assertSame($service, $factory->createService($classMetadata));
    }

    public function testServiceFactoryWithoutServiceId()
    {
        $classMetadata = $this->loadMetadataForClass('TQ\ExtDirect\Tests\Service\Services\Service2');

        $container = $this->createContainer();
        $container->expects($this->never())
                  ->method('get');

        $factory = new ContainerServiceFactory($container);

        $this->assertEquals(
            new \TQ\ExtDirect\Tests\Service\Services\Service2(),
            $factory->createService($classMetadata)
        );
    }

    public function testServiceFactoryWithoutServiceIdAndContainerAwareService()
    {
        $classMetadata = $this->loadMetadataForClass('TQ\ExtDirect\Tests\Service\Services\Service3');

        $container = $this->createContainer();
        $container->expects($this->never())
                  ->method('get');

        $factory = new ContainerServiceFactory($container);

        $service = new \TQ\ExtDirect\Tests\Service\Services\Service3();
        $service->setContainer($container);

        /** @var \TQ\ExtDirect\Tests\Service\Services\Service3 $createdService */
        $createdService = $factory->createService($classMetadata);

        $this->assertEquals($service, $createdService);
        $this->assertSame($container, $createdService->getContainer());
    }


    public function testServiceFactoryCannotInstantiateServiceWithComplicatedConstructor()
    {
        $classMetadata = $this->loadMetadataForClass('TQ\ExtDirect\Tests\Service\Services\Service4');

        $container = $this->createContainer();
        $factory   = new ContainerServiceFactory($container);

        $this->setExpectedException('\InvalidArgumentException', 'Cannot instantiate action');
        $factory->createService($classMetadata);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function createContainer()
    {
        /** @var \Symfony\Component\DependencyInjection\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        return $container;
    }

    /**
     * @param string $className
     * @return \Metadata\ClassMetadata|\TQ\ExtDirect\Metadata\ActionMetadata|null
     */
    protected function loadMetadataForClass($className)
    {
        $driver          = new AnnotationDriver(new AnnotationReader());
        $reflectionClass = new\ReflectionClass($className);
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);
        return $classMetadata;
    }

}
