<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 11:26
 */

namespace TQ\ExtDirect\Tests\Description;

use TQ\ExtDirect\Description\MethodDescription;

/**
 * Class MethodDescriptionTest
 *
 * @package TQ\ExtDirect\Tests\Description
 */
class MethodDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testMethodWithDefaultConstructor()
    {
        $m = new MethodDescription('method');
        $this->assertEquals('method', $m->getName());
        $this->assertFalse($m->isFormHandler());
        $this->assertEmpty($m->getParams());
        $this->assertFalse($m->hasNamedParams());
        $this->assertTrue($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name' => 'method',
            'len'  => 0
        )), json_encode($m));
    }

    public function testFormHandlerMethod()
    {
        $m = new MethodDescription('method', true);
        $this->assertEquals('method', $m->getName());
        $this->assertTrue($m->isFormHandler());
        $this->assertEmpty($m->getParams());
        $this->assertFalse($m->hasNamedParams());
        $this->assertTrue($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name'        => 'method',
            'formHandler' => true
        )), json_encode($m));
    }

    public function testMethodWithTwoParameters()
    {
        $m = new MethodDescription('method', false, array('a', 'b'));
        $this->assertEquals('method', $m->getName());
        $this->assertFalse($m->isFormHandler());
        $this->assertCount(2, $m->getParams());
        $this->assertFalse($m->hasNamedParams());
        $this->assertTrue($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name' => 'method',
            'len'  => 2
        )), json_encode($m));
    }

    public function testMethodWithTwoNamedParameters()
    {
        $m = new MethodDescription('method', false, array('a', 'b'), true);
        $this->assertEquals('method', $m->getName());
        $this->assertFalse($m->isFormHandler());
        $this->assertCount(2, $m->getParams());
        $this->assertTrue($m->hasNamedParams());
        $this->assertTrue($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name'   => 'method',
            'strict' => true,
            'params' => array('a', 'b')
        )), json_encode($m));
    }

    public function testMethodWithFreeParameters()
    {
        $m = new MethodDescription('method', false, array(), true, false);
        $this->assertEquals('method', $m->getName());
        $this->assertFalse($m->isFormHandler());
        $this->assertCount(0, $m->getParams());
        $this->assertTrue($m->hasNamedParams());
        $this->assertFalse($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name'   => 'method',
            'strict' => false,
            'params' => array()
        )), json_encode($m));
    }

    public function testFormHandlerMethodDoesNotUseParameters()
    {
        $m = new MethodDescription('method', true, array('a', 'b'));
        $this->assertEquals('method', $m->getName());
        $this->assertTrue($m->isFormHandler());
        $this->assertEmpty($m->getParams());
        $this->assertFalse($m->hasNamedParams());
        $this->assertTrue($m->isStrict());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'name'        => 'method',
            'formHandler' => true
        )), json_encode($m));
    }

    public function testFormHandlerMethodAddParameterFails()
    {
        $m = new MethodDescription('method', true);

        $this->setExpectedException('BadMethodCallException', 'Cannot add parameters to form handler methods');
        $m->addParam('a');
    }

    public function testFormHandlerMethodSetNamedParamsFails()
    {
        $m = new MethodDescription('method', true);

        $this->setExpectedException('BadMethodCallException', 'Cannot set named params on form handler methods');
        $m->setNamedParams(true);
    }

    public function testFormHandlerMethodASetStrictFails()
    {
        $m = new MethodDescription('method', true);

        $this->setExpectedException('BadMethodCallException', 'Cannot set strict params on form handler methods');
        $m->setStrict(false);
    }

    public function testAddParameter()
    {
        $m = new MethodDescription('method', false, array('a'));
        $m->addParam('b');
        $this->assertEquals(array('a', 'b'), $m->getParams());
    }

    public function testAddParameters()
    {
        $m = new MethodDescription('method', false, array('a'));
        $m->addParams(array('b', 'c'));
        $this->assertEquals(array('a', 'b', 'c'), $m->getParams());
    }

    public function testSetParameters()
    {
        $m = new MethodDescription('method', false, array('a'));
        $m->setParams(array('b', 'c'));
        $this->assertEquals(array('b', 'c'), $m->getParams());
    }
}
