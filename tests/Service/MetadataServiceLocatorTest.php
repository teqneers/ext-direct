<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:13
 */

namespace TQ\ExtDirect\Tests\Service;

use TQ\ExtDirect\Service\MetadataServiceLocator;

/**
 * Class MetadataServiceLocatorTest
 *
 * @package TQ\ExtDirect\Tests\Service
 */
class MetadataServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAllClassNames()
    {
        /** @var \Metadata\AdvancedMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject $metadataFactory */
        $metadataFactory = $this->getMock(
            'Metadata\AdvancedMetadataFactoryInterface',
            array('getMetadataForClass', 'getAllClassNames')
        );

        $metadataFactory->expects($this->once())
                        ->method('getAllClassNames')
                        ->willReturn(array());

        $serviceLocator = new MetadataServiceLocator($metadataFactory);
        $this->assertEquals(array(), $serviceLocator->getAllClassNames());
    }

    public function testGetMetadataForClass()
    {
        /** @var \Metadata\AdvancedMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject $metadataFactory */
        $metadataFactory = $this->getMock(
            'Metadata\AdvancedMetadataFactoryInterface',
            array('getMetadataForClass', 'getAllClassNames')
        );

        $metadataFactory->expects($this->once())
                        ->method('getMetadataForClass')
                        ->with($this->equalTo('A'))
                        ->willReturn(null);

        $serviceLocator = new MetadataServiceLocator($metadataFactory);
        $this->assertEquals(null, $serviceLocator->getMetadataForClass('A'));
    }
}
