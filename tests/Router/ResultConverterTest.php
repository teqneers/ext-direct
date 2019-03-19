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
        /** @var \JMS\Serializer\ArrayTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $transformer */
        $transformer = $this->createMock('\JMS\Serializer\ArrayTransformerInterface');

        $transformer->expects($this->never())
                    ->method('toArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createPartialMock('TQ\ExtDirect\Router\ServiceReference', []);

        $converter = new ResultConverter($transformer);
        $value     = 'value';
        $this->assertEquals($value, $converter->convert($service, $value));
    }

    public function testObjectIsConverted()
    {
        /** @var \JMS\Serializer\ArrayTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $transformer */
        $transformer = $this->createMock('\JMS\Serializer\ArrayTransformerInterface');

        $result = new ResultConverterTest_TestClass(1);

        $transformer->expects($this->once())
                   ->method('toArray')
                   ->with($this->equalTo($result))
                   ->willReturn(array(
                       'id' => 1
                   ));

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createMock('TQ\ExtDirect\Router\ServiceReference');

        $converter = new ResultConverter($transformer);
        $this->assertEquals(array(
            'id' => 1
        ), $converter->convert($service, $result));
    }

    public function testArrayIsConverted()
    {
        /** @var \JMS\Serializer\ArrayTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $transformer */
        $transformer = $this->createMock('\JMS\Serializer\ArrayTransformerInterface');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createMock('TQ\ExtDirect\Router\ServiceReference');

        $result = [
            new ResultConverterTest_TestClass(1),
            new ResultConverterTest_TestClass(2),
            new ResultConverterTest_TestClass(3)
        ];

        $transformer->expects($this->once())
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

        $converter = new ResultConverter($transformer);
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
        /** @var \JMS\Serializer\ArrayTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $transformer */
        $transformer = $this->createMock('\JMS\Serializer\ArrayTransformerInterface');

        $called = false;
        $result = function (\JMS\Serializer\ArrayTransformerInterface $s, \JMS\Serializer\SerializationContext $c) use (
            &$called,
            $transformer
        ) {
            $called = true;
            $this->assertSame($transformer, $s);
            $this->assertInstanceOf(\JMS\Serializer\SerializationContext::class, $c);
            return [
                'serialized_from_callable' => true,
            ];
        };

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->createMock('TQ\ExtDirect\Router\ServiceReference');

        $converter = new ResultConverter($transformer);
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


