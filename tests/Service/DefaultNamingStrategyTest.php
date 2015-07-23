<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 23.07.15
 * Time: 11:07
 */

namespace TQ\ExtDirect\Tests\Service;


use TQ\ExtDirect\Service\DefaultNamingStrategy;

class DefaultNamingStrategyTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @dataProvider actionNameProvider
     * @param string $actionName
     * @param string $expected
     */
    public function testConvertToClassName($actionName, $expected)
    {
        $strategy = new DefaultNamingStrategy();
        $this->assertEquals($expected, $strategy->convertToClassName($actionName));
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

    /**
     * @return array
     */
    public function actionNameProvider()
    {
        $data = array();
        foreach (self::classNamesActionNamesMap() as $className => $actionName) {
            $data[] = array($actionName, $className);
        }
        return $data;
    }
}
