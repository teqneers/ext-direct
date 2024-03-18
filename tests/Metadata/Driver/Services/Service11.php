<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver\Services;

use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service11
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action()
 * @Direct\Security("true")
 */
#[Direct\Action()]
#[Direct\Security("true")]
class Service11
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
     * @Direct\Security("true and false")
     */
    #[Direct\Method()]
    #[Direct\Security("true and false")]
    public function methodB()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Security(expression="true and true")
     */
    #[Direct\Method()]
    #[Direct\Security(expression: "true and false")]
    public function methodC()
    {
    }
}
