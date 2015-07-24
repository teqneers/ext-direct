<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:49
 */

namespace TQ\ExtDirect\Tests\Router;

/**
 * Class RequestFactoryTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateJsonRequest()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateFormRequest()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateFormUploadRequest()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateJsonRequestFromMalformedJsonStringFails()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateJsonRequestFromInvalidExtDirectJsonStringFails()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateJsonRequestFailsWhenInformationIsMissing()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateFormRequestFailsWhenInformationIsMissing()
    {
        $this->markTestIncomplete('TODO');
    }

    public function testCreateRequestFailsWhenNotPost()
    {
        $this->markTestIncomplete('TODO');
    }
}
