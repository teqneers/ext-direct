<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.07.15
 * Time: 11:44
 */

namespace TQ\ExtDirect\Tests\Router;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Router\Request as DirectRequest;
use TQ\ExtDirect\Router\RequestCollection;
use TQ\ExtDirect\Router\Router;

/**
 * Class RouterTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfulMethodCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'My.Action', 'myMethod', array('a', 'b'), false, false);

        $serviceResolver->expects($this->once())
                        ->method('getService')
                        ->with($this->equalTo($directRequest))
                        ->willReturn($service);

        $serviceResolver->expects(($this->once()))
                        ->method('getArguments')
                        ->with($this->equalTo($directRequest), $this->equalTo($httpRequest))
                        ->willReturn(array(
                            '__internal__directRequest' => $directRequest,
                            '__internal__httpRequest'   => $httpRequest,
                            'a'                         => 'a',
                            'b'                         => 'b'
                        ));

        $service->expects($this->any())
                ->method('hasSession')
                ->willReturn(true);
        $service->expects($this->once())
                ->method('__invoke')
                ->with(
                    $this->equalTo(
                        array(
                            $directRequest,
                            $httpRequest,
                            'a',
                            'b'
                        )
                    )
                )
                ->willReturn(1);

        $router   = new Router($serviceResolver);
        $response = $router->handle(new RequestCollection(array($directRequest)), $httpRequest);


        $this->assertInstanceOf('TQ\ExtDirect\Router\ResponseCollection', $response);
        $this->assertCount(1, $response);

        /** @var \TQ\ExtDirect\Router\RPCResponse $firstResponse */
        $firstResponse = $response->getFirst();
        $this->assertInstanceOf('TQ\ExtDirect\Router\RPCResponse', $firstResponse);

        $this->assertEquals(1, $firstResponse->getTid());
        $this->assertEquals('My.Action', $firstResponse->getAction());
        $this->assertEquals('myMethod', $firstResponse->getMethod());
        $this->assertEquals(1, $firstResponse->getResult());
    }

    public function testSuccessfulBatchedMethodsCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service1 = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service2 = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest    = new HttpRequest();
        $directRequest1 = new DirectRequest(1, 'My.Action', 'myMethod1', array('a', 'b'), false, false);
        $directRequest2 = new DirectRequest(2, 'My.Action', 'myMethod2', array('c', 'd'), false, false);

        $serviceResolver->expects($this->exactly(2))
                        ->method('getService')
                        ->withConsecutive(
                            array($this->equalTo($directRequest1)),
                            array($this->equalTo($directRequest2))
                        )
                        ->willReturnOnConsecutiveCalls($service1, $service2);

        $serviceResolver->expects(($this->exactly(2)))
                        ->method('getArguments')
                        ->withConsecutive(
                            array($this->equalTo($directRequest1), $this->equalTo($httpRequest)),
                            array($this->equalTo($directRequest2), $this->equalTo($httpRequest))
                        )
                        ->willReturnOnConsecutiveCalls(
                            array(
                                '__internal__directRequest' => $directRequest1,
                                '__internal__httpRequest'   => $httpRequest,
                                'a'                         => 'a',
                                'b'                         => 'b'
                            ),
                            array(
                                '__internal__directRequest' => $directRequest2,
                                '__internal__httpRequest'   => $httpRequest,
                                'a'                         => 'c',
                                'b'                         => 'd'
                            )
                        );

        $service1->expects($this->any())
                 ->method('hasSession')
                 ->willReturn(true);
        $service1->expects($this->once())
                 ->method('__invoke')
                 ->with(
                     $this->equalTo(
                         array(
                             $directRequest1,
                             $httpRequest,
                             'a',
                             'b'
                         )
                     )
                 )
                 ->willReturn(1);

        $service2->expects($this->any())
                 ->method('hasSession')
                 ->willReturn(true);
        $service2->expects($this->once())
                 ->method('__invoke')
                 ->with(
                     $this->equalTo(
                         array(
                             $directRequest2,
                             $httpRequest,
                             'c',
                             'd'
                         )
                     )
                 )
                 ->willReturn(2);

        $router   = new Router($serviceResolver);
        $response = $router->handle(new RequestCollection(array($directRequest1, $directRequest2)), $httpRequest);


        $this->assertInstanceOf('TQ\ExtDirect\Router\ResponseCollection', $response);
        $this->assertCount(2, $response);

        /** @var \TQ\ExtDirect\Router\RPCResponse $firstResponse */
        $firstResponse = $response->getAt(0);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RPCResponse', $firstResponse);

        $this->assertEquals(1, $firstResponse->getTid());
        $this->assertEquals('My.Action', $firstResponse->getAction());
        $this->assertEquals('myMethod1', $firstResponse->getMethod());
        $this->assertEquals(1, $firstResponse->getResult());

        /** @var \TQ\ExtDirect\Router\RPCResponse $secondResponse */
        $secondResponse = $response->getAt(1);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RPCResponse', $secondResponse);

        $this->assertEquals(2, $secondResponse->getTid());
        $this->assertEquals('My.Action', $secondResponse->getAction());
        $this->assertEquals('myMethod2', $secondResponse->getMethod());
        $this->assertEquals(2, $secondResponse->getResult());
    }

    public function testFailedMethodCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'My.Action', 'myMethod', array('a', 'b'), false, false);

        $serviceResolver->expects($this->once())
                        ->method('getService')
                        ->with($this->equalTo($directRequest))
                        ->willReturn($service);

        $serviceResolver->expects(($this->once()))
                        ->method('getArguments')
                        ->with($this->equalTo($directRequest), $this->equalTo($httpRequest))
                        ->willReturn(array(
                            '__internal__directRequest' => $directRequest,
                            '__internal__httpRequest'   => $httpRequest,
                            'a'                         => 'a',
                            'b'                         => 'b'
                        ));

        $service->expects($this->any())
                ->method('hasSession')
                ->willReturn(true);
        $exception = new \RuntimeException('Something has happened');
        $service->expects($this->once())
                ->method('__invoke')
                ->with(
                    $this->equalTo(
                        array(
                            $directRequest,
                            $httpRequest,
                            'a',
                            'b'
                        )
                    )
                )
                ->willThrowException($exception);

        $router   = new Router($serviceResolver);
        $response = $router->handle(new RequestCollection(array($directRequest)), $httpRequest);

        $this->assertInstanceOf('TQ\ExtDirect\Router\ResponseCollection', $response);
        $this->assertCount(1, $response);

        /** @var \TQ\ExtDirect\Router\ExceptionResponse $firstResponse */
        $firstResponse = $response->getFirst();
        $this->assertInstanceOf('TQ\ExtDirect\Router\ExceptionResponse', $firstResponse);

        $this->assertEquals(1, $firstResponse->getTid());
        $this->assertEquals('My.Action', $firstResponse->getAction());
        $this->assertEquals('myMethod', $firstResponse->getMethod());
        $this->assertSame($exception, $firstResponse->getException());
    }

    public function testPartiallySuccessfulBatchedMethodsCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service1 = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service2 = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest    = new HttpRequest();
        $directRequest1 = new DirectRequest(1, 'My.Action', 'myMethod1', array('a', 'b'), false, false);
        $directRequest2 = new DirectRequest(2, 'My.Action', 'myMethod2', array('c', 'd'), false, false);

        $serviceResolver->expects($this->exactly(2))
                        ->method('getService')
                        ->withConsecutive(
                            array($this->equalTo($directRequest1)),
                            array($this->equalTo($directRequest2))
                        )
                        ->willReturnOnConsecutiveCalls($service1, $service2);

        $serviceResolver->expects(($this->exactly(2)))
                        ->method('getArguments')
                        ->withConsecutive(
                            array($this->equalTo($directRequest1), $this->equalTo($httpRequest)),
                            array($this->equalTo($directRequest2), $this->equalTo($httpRequest))
                        )
                        ->willReturnOnConsecutiveCalls(
                            array(
                                '__internal__directRequest' => $directRequest1,
                                '__internal__httpRequest'   => $httpRequest,
                                'a'                         => 'a',
                                'b'                         => 'b'
                            ),
                            array(
                                '__internal__directRequest' => $directRequest2,
                                '__internal__httpRequest'   => $httpRequest,
                                'a'                         => 'c',
                                'b'                         => 'd'
                            )
                        );

        $service1->expects($this->any())
                 ->method('hasSession')
                 ->willReturn(true);
        $service1->expects($this->once())
                 ->method('__invoke')
                 ->with(
                     $this->equalTo(
                         array(
                             $directRequest1,
                             $httpRequest,
                             'a',
                             'b'
                         )
                     )
                 )
                 ->willReturn(1);

        $service2->expects($this->any())
                 ->method('hasSession')
                 ->willReturn(true);
        $exception = new \RuntimeException('Something has happened');
        $service2->expects($this->once())
                 ->method('__invoke')
                 ->with(
                     $this->equalTo(
                         array(
                             $directRequest2,
                             $httpRequest,
                             'c',
                             'd'
                         )
                     )
                 )
                 ->willThrowException($exception);

        $router   = new Router($serviceResolver);
        $response = $router->handle(new RequestCollection(array($directRequest1, $directRequest2)), $httpRequest);


        $this->assertInstanceOf('TQ\ExtDirect\Router\ResponseCollection', $response);
        $this->assertCount(2, $response);

        /** @var \TQ\ExtDirect\Router\RPCResponse $firstResponse */
        $firstResponse = $response->getAt(0);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RPCResponse', $firstResponse);

        $this->assertEquals(1, $firstResponse->getTid());
        $this->assertEquals('My.Action', $firstResponse->getAction());
        $this->assertEquals('myMethod1', $firstResponse->getMethod());
        $this->assertEquals(1, $firstResponse->getResult());

        /** @var \TQ\ExtDirect\Router\ExceptionResponse $secondResponse */
        $secondResponse = $response->getAt(1);
        $this->assertInstanceOf('TQ\ExtDirect\Router\ExceptionResponse', $secondResponse);

        $this->assertEquals(2, $secondResponse->getTid());
        $this->assertEquals('My.Action', $secondResponse->getAction());
        $this->assertEquals('myMethod2', $secondResponse->getMethod());
        $this->assertSame($exception, $secondResponse->getException());
    }

    public function testEventDispatcherIsCalledCorrectlyForSuccessfulCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'My.Action', 'myMethod', array('a', 'b'), false, false);

        $serviceResolver->expects($this->once())
                        ->method('getService')
                        ->with($this->equalTo($directRequest))
                        ->willReturn($service);

        $serviceResolver->expects(($this->once()))
                        ->method('getArguments')
                        ->with($this->equalTo($directRequest), $this->equalTo($httpRequest))
                        ->willReturn(array(
                            '__internal__directRequest' => $directRequest,
                            '__internal__httpRequest'   => $httpRequest,
                            'a'                         => 'a',
                            'b'                         => 'b'
                        ));

        $service->expects($this->any())
                ->method('hasSession')
                ->willReturn(true);
        $service->expects($this->once())
                ->method('__invoke')
                ->with(
                    $this->equalTo(
                        array(
                            $directRequest,
                            $httpRequest,
                            'a',
                            'b'
                        )
                    )
                )
                ->willReturn(1);


        $eventDispatcher = $this->createPartialMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            array(
                'dispatch',
                'addListener',
                'addSubscriber',
                'removeListener',
                'removeSubscriber',
                'getListeners',
                'getListenerPriority',
                'hasListeners',
            )
        );

        $eventDispatcher->expects($this->exactly(6))
                        ->method('dispatch')
                        ->withConsecutive(
                            array(
                                $this->equalTo('tq_extdirect.router.begin_request'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\BeginRequestEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.before_resolve'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\ServiceResolveEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.after_resolve'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\ServiceResolveEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.before_invoke'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\InvokeEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.after_invoke'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\InvokeEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.end_request'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\EndRequestEvent')
                            )
                        )
                        ->will($this->returnArgument(1));

        $router = new Router($serviceResolver, $eventDispatcher);
        $router->handle(new RequestCollection(array($directRequest)), $httpRequest);
    }


    public function testEventDispatcherIsCalledCorrectlyForFailedCall()
    {
        /** @var \TQ\ExtDirect\Router\ServiceResolverInterface|\PHPUnit_Framework_MockObject_MockObject $serviceResolver */
        $serviceResolver = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceResolverInterface',
            array('getService', 'getArguments')
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createPartialMock(
            'TQ\ExtDirect\Router\ServiceReference',
            array('__invoke', 'hasSession')
        );

        $httpRequest   = new HttpRequest();
        $directRequest = new DirectRequest(1, 'My.Action', 'myMethod', array('a', 'b'), false, false);

        $serviceResolver->expects($this->once())
                        ->method('getService')
                        ->with($this->equalTo($directRequest))
                        ->willReturn($service);

        $serviceResolver->expects(($this->once()))
                        ->method('getArguments')
                        ->with($this->equalTo($directRequest), $this->equalTo($httpRequest))
                        ->willReturn(array(
                            '__internal__directRequest' => $directRequest,
                            '__internal__httpRequest'   => $httpRequest,
                            'a'                         => 'a',
                            'b'                         => 'b'
                        ));

        $service->expects($this->any())
                ->method('hasSession')
                ->willReturn(true);
        $exception = new \RuntimeException('Something has happened');
        $service->expects($this->once())
                ->method('__invoke')
                ->with(
                    $this->equalTo(
                        array(
                            $directRequest,
                            $httpRequest,
                            'a',
                            'b'
                        )
                    )
                )
                ->willThrowException($exception);


        $eventDispatcher = $this->createPartialMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            array(
                'dispatch',
                'addListener',
                'addSubscriber',
                'removeListener',
                'removeSubscriber',
                'getListeners',
                'getListenerPriority',
                'hasListeners',
            )
        );

        $eventDispatcher->expects($this->exactly(6))
                        ->method('dispatch')
                        ->withConsecutive(
                            array(
                                $this->equalTo('tq_extdirect.router.begin_request'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\BeginRequestEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.before_resolve'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\ServiceResolveEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.after_resolve'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\ServiceResolveEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.before_invoke'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\InvokeEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.exception'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\ExceptionEvent')
                            ),
                            array(
                                $this->equalTo('tq_extdirect.router.end_request'),
                                $this->isInstanceOf('TQ\ExtDirect\Router\Event\EndRequestEvent')
                            )
                        )
                        ->will($this->returnArgument(1));

        $router = new Router($serviceResolver, $eventDispatcher);
        $router->handle(new RequestCollection(array($directRequest)), $httpRequest);
    }
}
