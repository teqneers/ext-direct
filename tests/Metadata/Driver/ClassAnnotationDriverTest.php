<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.12.15
 * Time: 14:55
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use TQ\ExtDirect\Metadata\Driver\ClassAnnotationDriver;

/**
 * Class ClassAnnotationDriverTest
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver
 */
class ClassAnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    protected function getDriver()
    {
        return new ClassAnnotationDriver(new AnnotationReader());
    }

    public function testGetAllClassNamesWithOneClass()
    {
        $driver = $this->getDriver();
        $driver->addClass('TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2');
        $classes = $driver->getAllClassNames();
        sort($classes, SORT_NATURAL);
        $this->assertEquals(
            array(
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2',
            ),
            $classes
        );
    }

    public function testGetAllClassNamesWithSeveralClasses()
    {
        $driver = $this->getDriver();
        $driver->addClasses([
            'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2',
            'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3',
        ]);
        $classes = $driver->getAllClassNames();
        sort($classes, SORT_NATURAL);
        $this->assertEquals(
            array(
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service2',
                'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service3',
            ),
            $classes
        );
    }

    public function testGetAllClassNamesWithNonActionClasses()
    {
        $driver = $this->getDriver();
        $driver->addClasses([
            'TQ\ExtDirect\Tests\Metadata\Driver\Services\Service1',
        ]);
        $classes = $driver->getAllClassNames();
        sort($classes, SORT_NATURAL);
        $this->assertEquals(
            array(),
            $classes
        );
    }
}
