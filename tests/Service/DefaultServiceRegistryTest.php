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

        $metadataFactory->expects($this->once())
                        ->method('getAllClassNames')
                        ->willReturn(array());

        $serviceRegistry = new DefaultServiceRegistry($metadataFactory, new DefaultNamingStrategy());
        $this->assertEquals(array(), $serviceRegistry->getAllMetadata());
    }

    public function testGetMetadataForService()
    {
        $metadataFactory = $this->createMetadataFactory();

        $metadataFactory->expects($this->once())
                        ->method('getMetadataForClass')
                        ->with($this->equalTo('A'))
                        ->willReturn(null);

        $serviceRegistry = new DefaultServiceRegistry($metadataFactory, new DefaultNamingStrategy());
        $this->assertEquals(null, $serviceRegistry->getMetadataForService('A'));
    }

    /**
     * @return \Metadata\AdvancedMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMetadataFactory()
    {
        /** @var \Metadata\AdvancedMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject $metadataFactory */
        $metadataFactory = $this->getMock(
            'Metadata\AdvancedMetadataFactoryInterface',
            array('getMetadataForClass', 'getAllClassNames')
        );
        return $metadataFactory;
    }
}
