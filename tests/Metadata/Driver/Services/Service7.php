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
 * Class Service7
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action()
 */
#[Direct\Action()]
class Service7
{
    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    protected function methodA()
    {
    }

    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    private function methodB()
    {
    }
}
