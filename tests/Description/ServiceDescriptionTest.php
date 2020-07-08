<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 11:55
 */

namespace TQ\ExtDirect\Tests\Description;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Description\ActionDescription;
use TQ\ExtDirect\Description\MethodDescription;
use TQ\ExtDirect\Description\ServiceDescription;

/**
 * Class ServiceDescriptionTest
 *
 * @package TQ\ExtDirect\Tests\Description
 */
class ServiceDescriptionTest extends TestCase
{
    public function testCreateDefaultConstructor()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $this->assertEquals('https://example.com/router', $d->getUrl());
        $this->assertEquals('Ext.global', $d->getNamespace());
        $this->assertEquals('remoting', $d->getType());
        $this->assertCount(1, $d->getActions());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'Ext.global',
                    'actions'   => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testCreateWithCustomNamespace()
    {
        $d  = new ServiceDescription('https://example.com/router', 'My.namespace');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $this->assertEquals('My.namespace', $d->getNamespace());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'My.namespace',
                    'actions'   => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testChangeUrl()
    {
        $d  = new ServiceDescription('https://example.com/router', 'My.namespace');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $d->setUrl('https://example.com/router2');

        $this->assertEquals('https://example.com/router2', $d->getUrl());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router2',
                    'namespace' => 'My.namespace',
                    'actions'   => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testChangeNamespace()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $d->setNamespace('My.namespace');

        $this->assertEquals('My.namespace', $d->getNamespace());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'My.namespace',
                    'actions'   => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testAddActions()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $a2 = new ActionDescription('action2', [new MethodDescription('method1')]);
        $a3 = new ActionDescription('action3', [new MethodDescription('method1')]);
        $d->addActions([$a2, $a3]);

        $this->assertCount(3, $d->getActions());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'Ext.global',
                    'actions'   => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                        'action2' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                        'action3' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testSetAction()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $a2 = new ActionDescription('action2', [new MethodDescription('method1')]);
        $a3 = new ActionDescription('action3', [new MethodDescription('method1')]);
        $d->setActions([$a2, $a3]);

        $this->assertCount(2, $d->getActions());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'Ext.global',
                    'actions'   => [
                        'action2' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                        'action3' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }

    public function testCreateWithCustomOptions()
    {
        $d  = new ServiceDescription(
            'https://example.com/router',
            'My.namespace',
            10,
            5,
            1000,
            5
        );
        $a1 = new ActionDescription('action1', [new MethodDescription('method1')]);
        $d->addAction($a1);

        $this->assertEquals('My.namespace', $d->getNamespace());
        $this->assertEquals(10, $d->getEnableBuffer());
        $this->assertEquals(5, $d->getBufferLimit());
        $this->assertEquals(1000, $d->getTimeout());
        $this->assertEquals(5, $d->getMaxRetries());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'         => 'remoting',
                    'url'          => 'https://example.com/router',
                    'namespace'    => 'My.namespace',
                    'enableBuffer' => 10,
                    'bufferLimit'  => 5,
                    'timeout'      => 1000,
                    'maxRetries'   => 5,
                    'actions'      => [
                        'action1' => [
                            [
                                'name' => 'method1',
                                'len'  => 0,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
    }
}
