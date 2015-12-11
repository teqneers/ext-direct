<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:13
 */

namespace TQ\ExtDirect\Tests\Service;

use TQ\ExtDirect\Service\DefaultNamingStrategy;
use TQ\ExtDirect\Service\DefaultServiceRegistry;

/**
 * Class DefaultServiceRegistryTest
 *
 * @package TQ\ExtDirect\Tests\Service
 */
class DefaultServiceRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAllMetadata()
    {
        $metadataFactory = $this->createMetadataFactory();

        $serviceRegistry = new DefaultServiceRegistry($metadataFactory, new DefaultNamingStrategy());
        $this->assertEquals(array(), $serviceRegistry->getAllServices());
    }

    public function testGetMetadataForService()
    {
        $metadataFactory = $this->createMetadataFactory();

        $metadataFactory->expects($this->once())
                        ->method('getMetadataForClass')
                        ->with($this->equalTo('A'))
                        ->willReturn(null);

        $serviceRegistry = new DefaultServiceRegistry($metadataFactory, new DefaultNamingStrategy());
        $serviceRegistry->addService('A');

        $this->assertEquals(null, $serviceRegistry->getService('A'));
    }

    /**
     * @return \Metadata\MetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMetadataFactory()
    {
        /** @var \Metadata\MetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject $metadataFactory */
        $metadataFactory = $this->getMock(
            'Metadata\MetadataFactoryInterface',
            array('getMetadataForClass')
        );
        return $metadataFactory;
    }
}
