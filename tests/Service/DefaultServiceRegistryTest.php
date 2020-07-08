<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:13
 */

namespace TQ\ExtDirect\Tests\Service;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Service\DefaultNamingStrategy;
use TQ\ExtDirect\Service\DefaultServiceRegistry;

/**
 * Class DefaultServiceRegistryTest
 *
 * @package TQ\ExtDirect\Tests\Service
 */
class DefaultServiceRegistryTest extends TestCase
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
     * @return \Metadata\MetadataFactoryInterface|MockObject
     */
    protected function createMetadataFactory()
    {
        /** @var \Metadata\MetadataFactoryInterface|MockObject $metadataFactory */
        $metadataFactory = $this->createMock(
            'Metadata\MetadataFactoryInterface'
        );
        return $metadataFactory;
    }
}
