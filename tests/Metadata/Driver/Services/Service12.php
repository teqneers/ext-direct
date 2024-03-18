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
 * Class Service12
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action()
 */
#[Direct\Action()]
class Service12
{
    /**
     * @Direct\Method(batched=null)
     */
    #[Direct\Method(batched: null)]
    public function methodA()
    {
    }
    /**
     * @Direct\Method(batched=true)
     */
    #[Direct\Method(batched: true)]
    public function methodB()
    {
    }

    /**
     * @Direct\Method(batched=false)
     */
    #[Direct\Method(batched: false)]
    public function methodC()
    {
    }
}
