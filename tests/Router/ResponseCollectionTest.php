<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 17:20
 */

namespace TQ\ExtDirect\Tests\Router;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Router\RPCResponse;
use TQ\ExtDirect\Router\ResponseCollection;

/**
 * Class ResponseCollectionTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class ResponseCollectionTest extends TestCase
{
    public function testIteration()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        foreach ($collection as $i => $r) {
            $this->assertSame(${'response' . ($i + 1)}, $r);
        }
    }

    public function testGetAll()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        $this->assertSame(
            array($response1, $response2, $response3),
            $collection->all()
        );
    }

    public function testCountable()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        $this->assertCount(3, $collection);
    }

    public function testEmpty()
    {
        $collection = new ResponseCollection(array());

        $this->assertCount(0, $collection);
        $this->assertEmpty($collection);
    }

    public function testGetFirstEmpty()
    {
        $collection = new ResponseCollection(array());
        $this->assertNull($collection->getFirst());
    }

    public function testGetFirst()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        $this->assertSame($response1, $collection->getFirst());
    }

    public function testGetAtEmpty()
    {
        $collection = new ResponseCollection(array());
        $this->assertNull($collection->getAt(-1));
        $this->assertNull($collection->getAt(0));
        $this->assertNull($collection->getAt(1));
    }

    public function testGetAt()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        $this->assertNull($collection->getAt(-1));
        $this->assertSame($response1, $collection->getAt(0));
        $this->assertSame($response2, $collection->getAt(1));
        $this->assertSame($response3, $collection->getAt(2));
        $this->assertNull($collection->getAt(3));
    }

    public function testJsonSerializeEmpty()
    {
        $collection = new ResponseCollection(array());

        $this->assertEquals(
            '[]',
            json_encode($collection)
        );
    }

    public function testJsonSerializeOneResponse()
    {
        $response   = new RPCResponse(1, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response));

        $this->assertEquals(
            '{"type":"rpc","tid":1,"action":"My.Action","method":"method","result":null}',
            json_encode($collection)
        );
    }

    public function testJsonSerialize()
    {
        $response1  = new RPCResponse(1, 'My.Action', 'method');
        $response2  = new RPCResponse(2, 'My.Action', 'method');
        $response3  = new RPCResponse(3, 'My.Action', 'method');
        $collection = new ResponseCollection(array($response1, $response2, $response3));

        $this->assertEquals(
            '[{"type":"rpc","tid":1,"action":"My.Action","method":"method","result":null},{"type":"rpc","tid":2,"action":"My.Action","method":"method","result":null},{"type":"rpc","tid":3,"action":"My.Action","method":"method","result":null}]',
            json_encode($collection)
        );
    }

}
