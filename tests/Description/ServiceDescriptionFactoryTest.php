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
use TQ\ExtDirect\Description\ServiceDescriptionFactory;
use TQ\ExtDirect\Metadata\Driver\PathAnnotationDriver;
use TQ\ExtDirect\Service\DefaultNamingStrategy;
use TQ\ExtDirect\Service\DefaultServiceRegistry;

/**
 * Class ServiceDescriptionFactoryTest
 *
 * @package TQ\ExtDirect\Tests\Description
 */
class ServiceDescriptionFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateDescription()
    {
        $factory = new ServiceDescriptionFactory(
            $this->createServiceRegistry(),
            new DefaultNamingStrategy(),
            'My.namespace'
        );

        $d = $factory->createServiceDescription('https://example.com/router');

        $this->assertEquals('https://example.com/router', $d->getUrl());
        $this->assertEquals('My.namespace', $d->getNamespace());
        $this->assertEquals('remoting', $d->getType());
        $this->assertCount(2, $d->getActions());

        $this->assertJsonStringEqualsJsonString(json_encode(array(
            'type'      => 'remoting',
            'url'       => 'https://example.com/router',
            'namespace' => 'My.namespace',
            'actions'   => array(
                'TQ.ExtDirect.Tests.Description.Services.Service1' => array(
                    array(
                        'name' => 'methodA',
                        'len'  => 0
                    ),
                    array(
                        'name'        => 'methodB',
                        'formHandler' => true
                    ),
                    array(
                        'name' => 'methodC',
                        'len'  => 1
                    ),
                    array(
                        'name' => 'methodD',
                        'len'  => 1
                    ),
                    array(
                        'name' => 'methodE',
                        'len'  => 1
                    ),
                    array(
                        'name' => 'methodF',
                        'len'  => 1
                    )
                ),
                'TQ.ExtDirect.Tests.Description.Services.Service4' => array(
                    array(
                        'name' => 'methodA',
                        'len'  => 0
                    ),
                    array(
                        'name'        => 'methodB',
                        'formHandler' => true
                    )
                )
            )
        )), json_encode($d));
    }

    /**
     * @return DefaultServiceRegistry
     */
    protected function createServiceRegistry()
    {
        return new DefaultServiceRegistry(
            new MetadataFactory(
                new PathAnnotationDriver(new AnnotationReader(), array(__DIR__ . '/Services'))
            )
        );
    }
}
