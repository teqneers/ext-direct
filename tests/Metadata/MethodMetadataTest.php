<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:01
 */

namespace TQ\ExtDirect\Tests\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Metadata\Driver\PathAnnotationDriver;

/**
 * Class MethodMetadataTest
 *
 * @package TQ\ExtDirect\Tests\Metadata
 */
class MethodMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $driver          = new AnnotationDriver(new AnnotationReader());
        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Services\Service1');
        $origMetadata    = $driver->loadMetadataForClass($reflectionClass);

        $serialized = serialize($origMetadata);
        /** @var \TQ\ExtDirect\Metadata\ActionMetadata $restoredMetadata */
        $restoredMetadata = unserialize($serialized);

        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $origMethodMetadata */
        /** @var \TQ\ExtDirect\Metadata\MethodMetadata $restoredMethodMetadata */
        $origMethodMetadata     = $origMetadata->methodMetadata['methodA'];
        $restoredMethodMetadata = $restoredMetadata->methodMetadata['methodA'];

        $this->assertEquals($origMethodMetadata->isMethod, $restoredMethodMetadata->isMethod);
        $this->assertEquals($origMethodMetadata->isFormHandler, $restoredMethodMetadata->isFormHandler);
        $this->assertEquals($origMethodMetadata->hasNamedParams, $restoredMethodMetadata->hasNamedParams);
        $this->assertEquals($origMethodMetadata->isStrict, $restoredMethodMetadata->isStrict);

        $this->assertEquals(count($origMethodMetadata->parameters), count($restoredMethodMetadata->parameters));
        $this->assertEquals(count($origMethodMetadata->constraints), count($restoredMethodMetadata->constraints));
    }
}
