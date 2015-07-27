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

        $converter = new ResultConverter($serializer);
        $value     = 'value';
        $this->assertEquals($value, $converter->convert($value));
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

        $converter = new ResultConverter($serializer);
        $this->assertEquals(array(
            'id' => 1
        ), $converter->convert($result));
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

