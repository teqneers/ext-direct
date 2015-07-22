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
    protected function getDriver()
    {
        return new AnnotationDriver(new AnnotationReader(), array(__DIR__ . '/Services'));
    }


    public function testClassWithoutAnnotation()
    {
        $driver = $this->getDriver();

        $reflectionClass = new\ReflectionClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service1');
        $classMetadata   = $driver->loadMetadataForClass($reflectionClass);

        $this->assertInstanceOf('Metadata\NullMetadata', $classMetadata);
    }
}
