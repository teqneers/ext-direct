<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.07.15
 * Time: 09:23
 */

namespace TQ\ExtDirect\Tests\Router;

use TQ\ExtDirect\Router\ResultConverter;

/**
 * Class ResultConverterTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ResultConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testNonObjectIsNotConverted()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('toArray'), array(), '', false);

        $serializer->expects($this->never())
                   ->method('toArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $converter = new ResultConverter($serializer);
        $value     = 'value';
        $this->assertEquals($value, $converter->convert($service, $value));
    }

    public function testObjectIsConverted()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('toArray'), array(), '', false);

        $result = new ResultConverterTest_TestClass(1);

        $serializer->expects($this->once())
                   ->method('toArray')
                   ->with($this->equalTo($result))
                   ->willReturn(array(
                       'id' => 1
                   ));

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $converter = new ResultConverter($serializer);
        $this->assertEquals(array(
            'id' => 1
        ), $converter->convert($service, $result));
    }

    public function testArrayIsConverted()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('toArray'), array(), '', false);

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $result = [
            new ResultConverterTest_TestClass(1),
            new ResultConverterTest_TestClass(2),
            new ResultConverterTest_TestClass(3)
        ];

        $serializer->expects($this->once())
                   ->method('toArray')
                   ->with($this->equalTo($result))
                   ->willReturn(
                       array(
                           array(
                               'id' => 1
                           ),
                           array(
                               'id' => 2
                           ),
                           array(
                               'id' => 3
                           )
                       ));

        $converter = new ResultConverter($serializer);
        $this->assertEquals(array(
            array(
                'id' => 1
            ),
            array(
                'id' => 2
            ),
            array(
                'id' => 3
            )
        ), $converter->convert($service, $result));
    }

    public function testCallableIsCalledForSerialization()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', ['toArray'], [], '', false);

        $called = false;
        $result = function (\JMS\Serializer\Serializer $s, \JMS\Serializer\SerializationContext $c) use (
            &$called,
            $serializer
        ) {
            $called = true;
            $this->assertSame($serializer, $s);
            $this->assertInstanceOf(\JMS\Serializer\SerializationContext::class, $c);
            return [
                'serialized_from_callable' => true,
            ];
        };

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $converter = new ResultConverter($serializer);
        $this->assertEquals(
            [
                'serialized_from_callable' => true,
            ],
            $converter->convert($service, $result)
        );
        $this->assertTrue($called);
    }
}

/**
 * Class ResultConverterTest_TestClass
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ResultConverterTest_TestClass
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}


