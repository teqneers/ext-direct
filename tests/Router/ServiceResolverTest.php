<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:48
 */

namespace TQ\ExtDirect\Tests\Router;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Metadata\Driver\PathAnnotationDriver;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Router\ServiceResolver;
use TQ\ExtDirect\Service\DefaultNamingStrategy;
use TQ\ExtDirect\Service\DefaultServiceRegistry;
use TQ\ExtDirect\Service\PathServiceLoader;

/**
 * Class ServiceResolverTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ServiceResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetService()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');

        /** @var \TQ\ExtDirect\Tests\Router\Services\Service1|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Tests\Router\Services\Service1');

        $serviceFactory->expects($this->once())
                       ->method('createService')
                       ->with($this->isInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata'))
                       ->willReturn($service);

        $resolver = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodA', array(), false,
            false);

        $serviceReference = $resolver->getService($directRequest);

        $this->assertSame($service, $serviceReference->getService());
    }

    public function testInvokeService()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');

        /** @var \TQ\ExtDirect\Tests\Router\Services\Service1|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Tests\Router\Services\Service1');

        $service->expects($this->once())
                ->method('methodA')
                ->with()
                ->willReturn(true);

        $serviceFactory->expects($this->once())
                       ->method('createService')
                       ->with($this->isInstanceOf('TQ\ExtDirect\Metadata\ActionMetadata'))
                       ->willReturn($service);

        $resolver = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodA', array(), false,
            false);

        $serviceReference = $resolver->getService($directRequest);

        $this->assertSame($service, $serviceReference->getService());
        $this->assertTrue($serviceReference());
    }

    public function testGetNoArguments()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodA', array(),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(0, $arguments);
    }

    public function testGetOneArgument()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodB', array('A'),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(1, $arguments);
        $this->assertEquals(array('a' => 'A'), $arguments);
    }

    public function testGetOneArgumentWithHttpRequest()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodC', array('A'),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(2, $arguments);
        $this->assertEquals(array('a' => 'A', '__internal__request' => $httpRequest), $arguments);
    }

    public function testGetOneArgumentWithDirectRequest()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodD', array('A'),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(2, $arguments);
        $this->assertEquals(array('a' => 'A', '__internal__request' => $directRequest), $arguments);
    }


    public function testGetOneArgumentWithHttpAndDirectRequest()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodE', array('A'),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(3, $arguments);
        $this->assertEquals(
            array(
                'a'                    => 'A',
                '__internal__request1' => $directRequest,
                '__internal__request2' => $httpRequest,
            ),
            $arguments
        );
    }

    public function testGetArgumentWithTooManyArguments()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodB', array('A', 'B'),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(1, $arguments);
        $this->assertEquals(array('a' => 'A'), $arguments);
    }

    public function testGetArgumentWithTooFewArguments()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');
        $resolver       = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodB', array(),
            false,
            false);

        $arguments = $resolver->getArguments($directRequest, $httpRequest);
        $this->assertCount(1, $arguments);
        $this->assertEquals(array('a' => null), $arguments);
    }

    public function testGetStaticService()
    {
        /** @var \TQ\ExtDirect\Service\ServiceFactory|\PHPUnit_Framework_MockObject_MockObject $serviceFactory */
        $serviceFactory = $this->getMock('TQ\ExtDirect\Service\ServiceFactory');

        $serviceFactory->expects($this->never())
                       ->method('createService');

        $resolver = new ServiceResolver(
            $this->createServiceRegistry(),
            $serviceFactory
        );

        $directRequest = new DirectRequest(1, 'TQ.ExtDirect.Tests.Router.Services.Service1', 'methodF', array(), false,
            false);

        $serviceReference = $resolver->getService($directRequest);

        $this->assertEquals('TQ\ExtDirect\Tests\Router\Services\Service1', $serviceReference->getService());
    }

    /**
     * @return DefaultServiceRegistry
     */
    protected function createServiceRegistry()
    {
        $registry = new DefaultServiceRegistry(
            new MetadataFactory(new AnnotationDriver(new AnnotationReader())),
            new DefaultNamingStrategy()
        );
        $registry->importServices(new PathServiceLoader([__DIR__ . '/Services']));
        return $registry;
    }
}
