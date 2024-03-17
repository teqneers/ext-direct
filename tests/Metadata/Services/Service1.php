<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Metadata\Services;

use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service1
 *
 * @package TQ\ExtDirect\Tests\Metadata\Services
 *
 * @Direct\Action(serviceId="app.direct.test", alias="alias")
 * @Direct\Security("true")
 */
#[Direct\Action(serviceId: "app.direct.test", alias: "alias")]
#[Direct\Security("true")]
class Service1
{
    /**
     * @Direct\Method(batched=true)
     * @Direct\Parameter("a", { @Assert\NotNull() })
     * @Direct\Security("true and true")
     * @Direct\Result(version=1)
     */
    #[Direct\Method(batched: true)]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    #[Direct\Security("true and true")]
    #[Direct\Result(version: 1)]
    public function methodA(mixed $a): true
    {
    }
}
