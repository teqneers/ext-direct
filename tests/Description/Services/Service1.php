<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:14
 */

namespace TQ\ExtDirect\Tests\Description\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;
use TQ\ExtDirect\Router\ArgumentValidationResult;
use TQ\ExtDirect\Router\Request as ExtDirectRequest;

/**
 * Class Service1
 *
 * @package TQ\ExtDirect\Tests\Description\Services
 *
 * @Direct\Action()
 */
#[Direct\Action()]
class Service1
{
    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    public function methodA()
    {
    }

    /**
     * @Direct\Method(true)
     */
    #[Direct\Method(true)]
    public function methodB()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodC(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodD(mixed $a, Request $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodE(mixed $a, ExtDirectRequest $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodF(
        mixed $a, ExtDirectRequest $request1, Request $request2
    ) {
    }
}
