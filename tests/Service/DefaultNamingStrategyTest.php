<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:07
 */

namespace TQ\ExtDirect\Tests\Service;


use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Service\DefaultNamingStrategy;

/**
 * Class DefaultNamingStrategyTest
 *
 * @package TQ\ExtDirect\Tests\Service
 */
class DefaultNamingStrategyTest extends TestCase
{
    /**
     * @dataProvider classNameProvider
     * @param string $className
     * @param string $expected
     */
    public function testConvertToActionName($className, $expected)
    {
        $strategy = new DefaultNamingStrategy();
        $this->assertEquals($expected, $strategy->convertToActionName($className));
    }

    protected static function classNamesActionNamesMap()
    {
        return array(
            'A'     => 'A',
            'A\B'   => 'A.B',
            'A\B\C' => 'A.B.C'
        );
    }

    /**
     * @return array
     */
    public function classNameProvider()
    {
        $data = array();
        foreach (self::classNamesActionNamesMap() as $className => $actionName) {
            $data[] = array($className, $actionName);
        }
        return $data;
    }
}
