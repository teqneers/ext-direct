<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 10:54
 */

namespace TQ\ExtDirect\Tests\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Metadata\Driver\PathAnnotationDriver;

/**
 * Class ActionMetadataTest
 *
 * @package TQ\ExtDirect\Tests\Metadata
 */
class ActionMetadataTest extends TestCase
{
    public function testSerialize()
    {
        $driver          = new AnnotationDriver(new AnnotationReader());
        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Services\Service1');
        $origMetadata    = $driver->loadMetadataForClass($reflectionClass);

        $serialized = serialize($origMetadata);
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $restoredMetadata */
        $restoredMetadata = unserialize($serialized);

        $this->assertEquals($origMetadata->isAction, $restoredMetadata->isAction);
        $this->assertEquals($origMetadata->serviceId, $restoredMetadata->serviceId);
        $this->assertEquals($origMetadata->alias, $restoredMetadata->alias);
        $this->assertEquals($origMetadata->authorizationExpression, $restoredMetadata->authorizationExpression);

        $this->assertEquals(count($origMetadata->methodMetadata), count($restoredMetadata->methodMetadata));
    }
}
