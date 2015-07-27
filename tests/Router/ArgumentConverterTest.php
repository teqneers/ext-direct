<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 27.07.15
 * Time: 10:00
 */

namespace TQ\ExtDirect\Tests\Router;


use TQ\ExtDirect\Router\ArgumentConverter;

/**
 * Class ArgumentConverterTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ArgumentConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testNonArrayArgumentIsNotConverted()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('fromArray'), array(), '', false);
        $serializer->expects($this->never())
                   ->method('fromArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', array('getParameter'), array(), '', false);
        $service->expects($this->once())
                ->method('getParameter')
                ->with($this->equalTo('a'))
                ->willReturn(
                    new \ReflectionParameter(
                        array('TQ\ExtDirect\Tests\Router\ArgumentConverterTest', 'serviceIntegerArgument'),
                        'a'
                    )
                );

        $converter = new ArgumentConverter($serializer);

        $this->assertEquals(
            array(
                'a' => 1
            ),
            $converter->convert($service, array(
                'a' => 1
            ))
        );
    }

    public function testArgumentsIsNotConvertedForNonExistingParameters()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('fromArray'), array(), '', false);
        $serializer->expects($this->never())
                   ->method('fromArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', array('getParameter'), array(), '', false);
        $service->expects($this->once())
                ->method('getParameter')
                ->with($this->equalTo('b'))
                ->willReturn(null);

        $converter = new ArgumentConverter($serializer);

        $this->assertEquals(
            array(
                'b' => 1
            ),
            $converter->convert($service, array(
                'b' => 1
            ))
        );
    }

    public function testArrayArgumentIsNotConvertedForNonTypedParameter()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('fromArray'), array(), '', false);
        $serializer->expects($this->never())
                   ->method('fromArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', array('getParameter'), array(), '', false);
        $service->expects($this->once())
                ->method('getParameter')
                ->with($this->equalTo('a'))
                ->willReturn(
                    new \ReflectionParameter(
                        array('TQ\ExtDirect\Tests\Router\ArgumentConverterTest', 'serviceArrayArgument'),
                        'a'
                    )
                );

        $converter = new ArgumentConverter($serializer);

        $this->assertEquals(
            array(
                'a' => array(1, 2, 3)
            ),
            $converter->convert($service, array(
                'a' => array(1, 2, 3)
            ))
        );
    }

    public function testArrayArgumentIsConvertedForTypedParameter()
    {
        $argument = new ArgumentConverterTest_TestClass(1);

        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('fromArray'), array(), '', false);
        $serializer->expects($this->once())
                   ->method('fromArray')
                   ->with(
                       $this->equalTo(array('id' => 1)),
                       $this->equalTo('TQ\ExtDirect\Tests\Router\ArgumentConverterTest_TestClass')
                   )
                   ->willReturn($argument);

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', array('getParameter'), array(), '', false);
        $service->expects($this->once())
                ->method('getParameter')
                ->with($this->equalTo('a'))
                ->willReturn(
                    new \ReflectionParameter(
                        array('TQ\ExtDirect\Tests\Router\ArgumentConverterTest', 'serviceObjectArgument'),
                        'a'
                    )
                );

        $converter = new ArgumentConverter($serializer);

        $this->assertEquals(
            array(
                'a' => $argument

            ),
            $converter->convert($service, array(
                'a' => array('id' => 1)
            ))
        );
    }

    public function testArgumentsIsNotConvertedForInternalParameters()
    {
        /** @var \JMS\Serializer\Serializer|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this->getMock('JMS\Serializer\Serializer', array('fromArray'), array(), '', false);
        $serializer->expects($this->never())
                   ->method('fromArray');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', array('getParameter'), array(), '', false);
        $service->expects($this->never())
                ->method('getParameter');

        $converter = new ArgumentConverter($serializer);

        $this->assertEquals(
            array(
                '__internal__a' => 1
            ),
            $converter->convert($service, array(
                '__internal__a' => 1
            ))
        );
    }

    /**
     * @param int $a
     */
    public static function serviceIntegerArgument($a)
    {
    }

    /**
     * @param array $a
     */
    public static function serviceArrayArgument(array $a)
    {
    }

    /**
     * @param ArgumentConverterTest_TestClass $a
     */
    public static function serviceObjectArgument(ArgumentConverterTest_TestClass $a)
    {
    }
}


/**
 * Class ArgumentConverterTest_TestClass
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ArgumentConverterTest_TestClass
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
