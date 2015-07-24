<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:48
 */

namespace TQ\ExtDirect\Tests\Router;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;
use TQ\ExtDirect\Metadata\Driver\AnnotationDriver;
use TQ\ExtDirect\Service\MetadataServiceLocator;

/**
 * Class ServiceResolverTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ServiceResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetService()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testGetArguments()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @return MetadataServiceLocator
     */
    protected function createServiceLocator()
    {
        return new MetadataServiceLocator(
            new MetadataFactory(
                new AnnotationDriver(new AnnotationReader(), array(__DIR__ . '/Services'))
            )
        );
    }
}
