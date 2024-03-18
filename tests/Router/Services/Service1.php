<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:53
 */

namespace TQ\ExtDirect\Tests\Router\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;
use TQ\ExtDirect\Router\ArgumentValidationResult;
use TQ\ExtDirect\Router\Request as ExtDirectRequest;

/**
 * Class Service1
 *
 * @package TQ\ExtDirect\Tests\Router\Services
 *
 * @Direct\Action("app.direct.test")
 */
#[Direct\Action("app.direct.test")]
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
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodB(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodC(mixed $a, Request $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodD(mixed $a, ExtDirectRequest $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodE(
        mixed $a, ExtDirectRequest $request1, Request $request2
    ) {
    }

    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    public static function methodF()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodG(mixed $a, ArgumentValidationResult $result = null)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodH(mixed $a, ArgumentValidationResult $result)
    {
    }
}
