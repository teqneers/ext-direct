<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 28.07.15
 * Time: 17:07
 */

namespace TQ\ExtDirect\Tests\Router;

use PHPUnit\Framework\TestCase;
use TQ\ExtDirect\Router\EventResponse;

/**
 * Class EventResponseTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class EventResponseTest extends TestCase
{
    public function testJsonSerialize()
    {
        $response = new EventResponse('newdata', array(1, 2, 3));

        $this->assertEquals(
            '{"type":"event","name":"newdata","data":[1,2,3]}',
            json_encode($response)
        );
    }
}
