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
 * Class Service10
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action()
 */
#[Direct\Action()]
class Service10
{
    /**
     * @Direct\Method()
     * @Direct\Result(groups={"a", "b"})
     */
    #[Direct\Method()]
    #[Direct\Result(groups: ["a", "b"])]
    public function methodA()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Result(attributes={"a": 1, "b": 2})
     */
    #[Direct\Method()]
    #[Direct\Result(attributes: ["a" => 1, "b" => 2])]
    public function methodB()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Result(version=1)
     */
    #[Direct\Method()]
    #[Direct\Result(version: 1)]
    public function methodC()
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Result(groups={"a", "b"}, attributes={"a": 1, "b": 2}, version=1)
     */
    #[Direct\Method()]

    #[Direct\Result(
      groups: ["a", "b"],
      attributes: ["a" => 1, "b" => 2],
      version: 1
    )]
    public function methodD()
    {
    }
}
