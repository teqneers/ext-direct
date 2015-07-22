<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:29
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;

/**
 * Class AnnotationDriverTest
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testClassWithoutAnnotation()
    {
        $driver = new AnnotationDriver(new AnnotationReader(), array(__DIR__ . '/Services'));

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service1');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('Metadata\NullMetadata', $classMetadata);
    }
}
