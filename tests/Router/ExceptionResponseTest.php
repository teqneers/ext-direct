<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 17:09
 */

namespace TQ\ExtDirect\Tests\Router;

use TQ\ExtDirect\Router\ExceptionResponse;
use TQ\ExtDirect\Router\Request;

/**
 * Class ExceptionResponseTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ExceptionResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $e        = new \RuntimeException('This is my exception', 1);
        $response = new ExceptionResponse(1, 'My.Action', 'method', $e, false);

        $this->assertEquals(
            '{"type":"exception","tid":1,"action":"My.Action","method":"method","where":"","message":"Internal Server Error","code":1}',
            json_encode($response)
        );
    }

    public function testJsonSerializeDebug()
    {
        $e        = new \RuntimeException('This is my exception', 1);
        $response = new ExceptionResponse(1, 'My.Action', 'method', $e, true);

        $this->assertStringStartsWith(
            '{"type":"exception","tid":1,"action":"My.Action","method":"method","where":"#0',
            json_encode($response)
        );
    }

    public function testCreateFromRequest()
    {
        $request  = new Request(2, 'My.Action', 'method', array(1, 2, 3), false, false);
        $e        = new \RuntimeException('This is my exception', 1);
        $response = ExceptionResponse::fromRequest($request, $e);

        $this->assertEquals(
            '{"type":"exception","tid":2,"action":"My.Action","method":"method","where":"","message":"Internal Server Error","code":1}',
            json_encode($response)
        );
    }

    public function testCreateFromRequestDebug()
    {
        $request  = new Request(2, 'My.Action', 'method', array(1, 2, 3), false, false);
        $e        = new \RuntimeException('This is my exception', 1);
        $response = ExceptionResponse::fromRequest($request, $e, true);

        $this->assertStringStartsWith(
            '{"type":"exception","tid":2,"action":"My.Action","method":"method","where":"#0',
            json_encode($response)
        );
    }
}
