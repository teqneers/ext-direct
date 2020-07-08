<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 16:48
 */

namespace TQ\ExtDirect\Tests\Http;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Http\UploadResponse;
use TQ\ExtDirect\Router\RPCResponse;

/**
 * Class UploadResponseTest
 *
 * @package TQ\ExtDirect\Tests\Http
 */
class UploadResponseTest extends TestCase
{
    public function testUploadResponse()
    {
        $directResponse = new RPCResponse(1, 'My.Action', 'method', array('success' => true));
        $httpResponse   = new UploadResponse($directResponse);

        $this->expectOutputString(<<<'OUT'
<html><body><textarea>{"type":"rpc","tid":1,"action":"My.Action","method":"method","result":{"success":true}}</textarea></body></html>
OUT
        );
        $httpResponse->sendContent();
    }

    public function testQuoteEscaping()
    {
        $directResponse = new RPCResponse(1, 'My.Action', 'method', array('content' => '&quot;'));
        $httpResponse   = new UploadResponse($directResponse);

        $this->expectOutputString(<<<'OUT'
<html><body><textarea>{"type":"rpc","tid":1,"action":"My.Action","method":"method","result":{"content":"\u0026quot;"}}</textarea></body></html>
OUT
        );
        $httpResponse->sendContent();
    }
}
