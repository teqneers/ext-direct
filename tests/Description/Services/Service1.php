<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:14
 */

namespace TQ\ExtDirect\Tests\Description\Services;

use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service1
 *
 * @package TQ\ExtDirect\Tests\Description\Services
 *
 * @Direct\Action()
 */
class Service1
{
    /**
     * @Direct\Method()
     */
    public function methodA()
    {
    }

    /**
     * @Direct\Method(true)
     */
    public function methodB()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     *
     * @param mixed $a
     */
    public function methodC($a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     *
     * @param mixed                                     $a
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function methodD($a, \Symfony\Component\HttpFoundation\Request $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     *
     * @param mixed                        $a
     * @param \TQ\ExtDirect\Router\Request $request
     */
    public function methodE($a, \TQ\ExtDirect\Router\Request $request)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     *
     * @param mixed                                     $a
     * @param \TQ\ExtDirect\Router\Request              $request1
     * @param \Symfony\Component\HttpFoundation\Request $request2
     */
    public function methodF(
        $a,
        \TQ\ExtDirect\Router\Request $request1,
        \Symfony\Component\HttpFoundation\Request $request2
    ) {
    }
}
