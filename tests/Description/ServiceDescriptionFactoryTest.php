<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:09
 */

namespace TQ\ExtDirect\Tests\Description;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;
use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Description\ServiceDescriptionFactory;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Service\DefaultNamingStrategy;
use TQ\ExtDirect\Service\DefaultServiceRegistry;
use TQ\ExtDirect\Service\PathServiceLoader;

/**
 * Class ServiceDescriptionFactoryTest
 *
 * @package TQ\ExtDirect\Tests\Description
 */
class ServiceDescriptionFactoryTest extends TestCase
{

    public function testCreateDescription()
    {
        $factory = new ServiceDescriptionFactory(
            $this->createServiceRegistry(),
            'My.namespace'
        );

        $d = $factory->createServiceDescription('https://example.com/router');

        $this->assertEquals('https://example.com/router', $d->getUrl());
        $this->assertEquals('My.namespace', $d->getNamespace());
        $this->assertEquals('remoting', $d->getType());
        $this->assertCount(2, $d->getActions());

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'type'      => 'remoting',
                    'url'       => 'https://example.com/router',
                    'namespace' => 'My.namespace',
                    'actions'   => [
                        'TQ.ExtDirect.Tests.Description.Services.Service1' => [
                            [
                                'name' => 'methodA',
                                'len'  => 0,
                            ],
                            [
                                'name'        => 'methodB',
                                'formHandler' => true,
                            ],
                            [
                                'name' => 'methodC',
                                'len'  => 1,
                            ],
                            [
                                'name' => 'methodD',
                                'len'  => 1,
                            ],
                            [
                                'name' => 'methodE',
                                'len'  => 1,
                            ],
                            [
                                'name' => 'methodF',
                                'len'  => 1,
                            ],
                        ],
                        'TQ.ExtDirect.Tests.Description.Services.Service4' => [
                            [
                                'name' => 'methodA',
                                'len'  => 0,
                            ],
                            [
                                'name'        => 'methodB',
                                'formHandler' => true,
                            ],
                        ],
                    ],
                ]
            ),
            json_encode($d)
        );
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

    public function testServiceParametersAreSetOnServiceDefinition()
    {
        $factory = new ServiceDescriptionFactory(
            $this->createServiceRegistry(),
            'My.namespace',
            10,
            5,
            1000,
            5
        );

        $d = $factory->createServiceDescription('https://example.com/router');
        $this->assertEquals(10, $d->getEnableBuffer());
        $this->assertEquals(5, $d->getBufferLimit());
        $this->assertEquals(1000, $d->getTimeout());
        $this->assertEquals(5, $d->getMaxRetries());
    }
}
