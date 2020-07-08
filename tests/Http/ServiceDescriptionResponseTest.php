<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 16:32
 */

namespace TQ\ExtDirect\Tests\Http;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Description\ActionDescription;
use TQ\ExtDirect\Description\MethodDescription;
use TQ\ExtDirect\Description\ServiceDescription;
use TQ\ExtDirect\Http\ServiceDescriptionResponse;

/**
 * Class ServiceDescriptionResponseTest
 *
 * @package TQ\ExtDirect\Tests\Http
 */
class ServiceDescriptionResponseTest extends TestCase
{
    public function testDefaultServiceDescriptionResponse()
    {
        $d = $this->createServiceDescription();

        $response = new ServiceDescriptionResponse($d);

        $this->expectOutputString(<<<'OUT'
var Ext = Ext || {};
Ext.app = Ext.app || {};
Ext.app.REMOTING_API = {"type":"remoting","url":"https:\/\/example.com\/router","namespace":"Ext.global","actions":{"action1":[{"name":"method1","len":0}]}};
OUT
        );
        $response->sendContent();
    }

    public function testChangeDescriptor()
    {
        $d = $this->createServiceDescription();

        $response = new ServiceDescriptionResponse($d);
        $response->setDescriptor('My.app.REMOTE');

        $this->expectOutputString(<<<'OUT'
var My = My || {};
My.app = My.app || {};
My.app.REMOTE = {"type":"remoting","url":"https:\/\/example.com\/router","namespace":"Ext.global","actions":{"action1":[{"name":"method1","len":0}]}};
OUT
        );
        $response->sendContent();
    }

    public function testSimpleDescriptor()
    {
        $d = $this->createServiceDescription();

        $response = new ServiceDescriptionResponse($d, 'REMOTE');

        $this->expectOutputString(<<<'OUT'
var REMOTE = {"type":"remoting","url":"https:\/\/example.com\/router","namespace":"Ext.global","actions":{"action1":[{"name":"method1","len":0}]}};
OUT
        );
        $response->sendContent();
    }

    /**
     * @return ServiceDescription
     */
    protected function createServiceDescription()
    {
        $d  = new ServiceDescription('https://example.com/router');
        $a1 = new ActionDescription('action1', array(new MethodDescription('method1')));
        $d->addAction($a1);
        return $d;
    }
}
