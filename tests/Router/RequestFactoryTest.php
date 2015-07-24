<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:49
 */

namespace TQ\ExtDirect\Tests\Router;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use TQ\ExtDirect\Router\RequestFactory;

/**
 * Class RequestFactoryTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateJsonRequest()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '{"action":"My.service.Action","method":"method","data":["a", "b"],"type":"rpc","tid":1}'
        );

        $directRequest = $factory->createRequest($httpRequest);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RequestCollection', $directRequest);
        $this->assertCount(1, $directRequest);
        $this->assertFalse($directRequest->isForm());
        $this->assertFalse($directRequest->isUpload());
        $this->assertFalse($directRequest->isFormUpload());
    }

    public function testCreateBatchedJsonRequest()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '['
            . '{"action":"My.service.Action","method":"method","data":["a", "b"],"type":"rpc","tid":1},'
            . '{"action":"My.service.Action","method":"method","data":["c", "d"],"type":"rpc","tid":2}'
            . ']'
        );

        $directRequest = $factory->createRequest($httpRequest);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RequestCollection', $directRequest);
        $this->assertCount(2, $directRequest);
    }

    public function testCreateFormRequest()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createFormRequest(
            array(
                'extTID'    => 1,
                'extAction' => 'My.service.Action',
                'extMethod' => 'method',
                'extType'   => 'rpc'
            )
        );

        $directRequest = $factory->createRequest($httpRequest);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RequestCollection', $directRequest);
        $this->assertCount(1, $directRequest);
        $this->assertTrue($directRequest->isForm());
        $this->assertFalse($directRequest->isUpload());
        $this->assertFalse($directRequest->isFormUpload());
    }

    public function testCreateFormUploadRequest()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createFormRequest(
            array(
                'extTID'    => 1,
                'extAction' => 'My.service.Action',
                'extMethod' => 'method',
                'extType'   => 'rpc',
                'extUpload' => 'true'
            )
        );

        $directRequest = $factory->createRequest($httpRequest);
        $this->assertInstanceOf('TQ\ExtDirect\Router\RequestCollection', $directRequest);
        $this->assertCount(1, $directRequest);
        $this->assertTrue($directRequest->isForm());
        $this->assertTrue($directRequest->isUpload());
        $this->assertTrue($directRequest->isFormUpload());
    }

    public function testCreateJsonRequestFromMalformedJsonStringFails()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '{]'
        );

        $this->setExpectedException('TQ\ExtDirect\Router\Exception\BadRequestException', 'The JSON string in invalid');
        $factory->createRequest($httpRequest);
    }

    public function testCreateJsonRequestFromInvalidExtDirectJsonStringFails()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '"a"'
        );

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'The Ext direct request is invalid'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateJsonRequestFailsWhenInformationIsMissing()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '{"action":"My.service.Action","method":"method","data":["a", "b"],"type":"rpc"}'
        );

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'The Ext direct request is missing vital information'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateFormRequestFailsWhenInformationIsMissing()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createFormRequest(
            array(
                'extAction' => 'My.service.Action',
                'extMethod' => 'method',
                'extType'   => 'rpc',
                'extUpload' => 'true'
            )
        );

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'The Ext direct request is missing vital information'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateJsonRequestFailsWhenTypeIsNotRpc()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createJsonRequest(
            '{"action":"My.service.Action","method":"method","data":["a", "b"],"type":"foo","tid":1}'
        );

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'The Ext direct request is missing vital information'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateFormRequestFailsWhenWhenTypeIsNotRpc()
    {
        $factory     = new RequestFactory();
        $httpRequest = $this->createFormRequest(
            array(
                'extTID'    => 1,
                'extAction' => 'My.service.Action',
                'extMethod' => 'method',
                'extType'   => 'foo',
                'extUpload' => 'true'
            )
        );

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'The Ext direct request is missing vital information'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateRequestFailsWhenMethodIsGet()
    {
        $factory     = new RequestFactory();
        $httpRequest = new HttpRequest();
        $httpRequest->setMethod(HttpRequest::METHOD_GET);

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'Only POST requests are allowed'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateRequestFailsWhenMethodIsPut()
    {
        $factory     = new RequestFactory();
        $httpRequest = new HttpRequest();
        $httpRequest->setMethod(HttpRequest::METHOD_PUT);

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'Only POST requests are allowed'
        );
        $factory->createRequest($httpRequest);
    }

    public function testCreateRequestFailsWhenMethodIsDelete()
    {
        $factory     = new RequestFactory();
        $httpRequest = new HttpRequest();
        $httpRequest->setMethod(HttpRequest::METHOD_DELETE);

        $this->setExpectedException(
            'TQ\ExtDirect\Router\Exception\BadRequestException',
            'Only POST requests are allowed'
        );
        $factory->createRequest($httpRequest);
    }

    /**
     * @param string $json
     * @return HttpRequest
     */
    private function createJsonRequest($json)
    {
        $httpRequest = new HttpRequest(
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            $json
        );
        $httpRequest->setMethod(HttpRequest::METHOD_POST);
        $httpRequest->headers->set('Content-Type', 'application/json');
        return $httpRequest;
    }

    /**
     * @param array $data
     * @return HttpRequest
     */
    private function createFormRequest(array $data)
    {
        $httpRequest = new HttpRequest(
            array(),
            $data,
            array(),
            array(),
            array(),
            array(),
            null
        );

        $httpRequest->setMethod(HttpRequest::METHOD_POST);
        $httpRequest->headers->set('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        return $httpRequest;
    }
}
