<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:53
 */

namespace TQ\ExtDirect\Tests\Router\Services;

use Symfony\Component\HttpFoundation\Request;
use TQ\ExtDirect\Annotation as Direct;
use TQ\ExtDirect\Router\Request as ExtDirectRequest;

/**
 * Class Service2
 *
 * @package TQ\ExtDirect\Tests\Router\Services
 */
class Service2
{
    /**
     * @Direct\Method(true)
     */
    #[Direct\Method(true)]
    public function methodA(Request $request)
    {
    }

    /**
     * @Direct\Method(true)
     */
    #[Direct\Method(true)]
    public function methodB(ExtDirectRequest $request)
    {
    }

    /**
     * @Direct\Method(true)
     */
    #[Direct\Method(true)]
    public function methodC(ExtDirectRequest $request1, Request $request2)
    {
    }
}
