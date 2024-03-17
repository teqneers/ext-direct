<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver\Services;

use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service5
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action("app.direct.test")
 */
#[Direct\Action("app.direct.test")]
class Service5
{
    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ])]
    public function methodA(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", constraints={ @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", constraints: [ new Assert\NotNull() ])]
    public function methodB(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter(name="a", constraints={ @Assert\NotNull() })
     */
    #[Direct\Method()]
    #[Direct\Parameter(name: "a", constraints: [ new Assert\NotNull() ])]
    public function methodC(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() }, {"myGroup"})
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ], [ "myGroup" ])]
    public function methodD(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() }, validationGroups="myGroup")
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ], validationGroups: "myGroup" )]
    public function methodE(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter(name="a", constraints={ @Assert\NotNull() }, validationGroups="myGroup")
     */
    #[Direct\Method()]

    #[Direct\Parameter(
      name: "a",
      constraints: [ new Assert\NotNull() ],
      validationGroups: "myGroup"
    )]
    public function methodF(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() }, validationGroups={"myGroup"})
     */
    #[Direct\Method()]
    #[Direct\Parameter("a", [ new Assert\NotNull() ], validationGroups: [ "myGroup" ])]
    public function methodG(mixed $a)
    {
    }

    /**
     * @Direct\Method()
     * @Direct\Parameter(name="a", constraints={ @Assert\NotNull() }, validationGroups={"myGroup"})
     */
    #[Direct\Method()]

    #[Direct\Parameter(
      name: "a",
      constraints: [ new Assert\NotNull() ],
      validationGroups: [ "myGroup" ]
    )]
    public function methodH(mixed $a)
    {
    }
}
