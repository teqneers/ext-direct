<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 11:55
 */

namespace TQ\ExtDirect\Tests\Description;

use TQ\ExtDirect\Description\ActionDescription;
use TQ\ExtDirect\Description\MethodDescription;
use TQ\ExtDirect\Description\ServiceDescription;

/**
 * Class ServiceDescriptionTest
 *
 * @package TQ\ExtDirect\Tests\Description
 */
class ServiceDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDefaultConstructor()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $this->assertEquals('https://example.com/router', $d->getUrl());
        $this->assertEquals('Ext.global', $d->getNamespace());
        $this->assertEquals('remoting', $d->getType());
        $this->assertCount(1, $d->getActions());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'Ext.global',
            'actions'   => array(
                'action1' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }

    public function testCreateWithCustomNamespace()
    {
        $d  = new ServiceDescription('https://example.com/router', 'My.namespace');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $this->assertEquals('My.namespace', $d->getNamespace());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'My.namespace',
            'actions'   => array(
                'action1' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }

    public function testChangeUrl()
    {
        $d  = new ServiceDescription('https://example.com/router', 'My.namespace');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $d->setUrl('https://example.com/router2');

        $this->assertEquals('https://example.com/router2', $d->getUrl());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router2',
            'namespace' => 'My.namespace',
            'actions'   => array(
                'action1' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }

    public function testChangeNamespace()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $d->setNamespace('My.namespace');

        $this->assertEquals('My.namespace', $d->getNamespace());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'My.namespace',
            'actions'   => array(
                'action1' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }

    public function testAddActions()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $a2 = new ActionDescription('action2', array(new MethodDescription('method1')));
        $a3 = new ActionDescription('action3', array(new MethodDescription('method1')));
        $d->addActions(array($a2, $a3));

        $this->assertCount(3, $d->getActions());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'Ext.global',
            'actions'   => array(
                'action1' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                ),
                'action2' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                ),
                'action3' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }

    public function testSetAction()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);

        $a2 = new ActionDescription('action2', array(new MethodDescription('method1')));
        $a3 = new ActionDescription('action3', array(new MethodDescription('method1')));
        $d->setActions(array($a2, $a3));

        $this->assertCount(2, $d->getActions());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'Ext.global',
            'actions'   => array(
                'action2' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                ),
                'action3' => array(
                    array(
                        'name' => 'method1',
                        'len'  => 0
                    )
                )
            )
        )), json_encode($d));
    }
}
