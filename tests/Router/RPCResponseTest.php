<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 17:04
 */

namespace TQ\ExtDirect\Tests\Router;

use TQ\ExtDirect\Router\Request;
use TQ\ExtDirect\Router\RPCResponse;

/**
 * Class RPCResponseTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class RPCResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $response = new RPCResponse(1, 'My.Action', 'method', array(1, 2, 3));

        $this->assertEquals(
            '{"type":"rpc","tid":1,"action":"My.Action","method":"method","result":[1,2,3]}',
            json_encode($response)
        );
    }

    public function testCreateFromRequest()
    {
        $request  = new Request(2, 'My.Action', 'method', array(1, 2, 3), false, false);
        $response = RPCResponse::fromRequest($request, array(4, 5, 6));

        $this->assertEquals(
            '{"type":"rpc","tid":2,"action":"My.Action","method":"method","result":[4,5,6]}',
            json_encode($response)
        );
    }
}
