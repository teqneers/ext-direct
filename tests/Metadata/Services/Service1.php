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
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action("app.direct.test")
 */
class Service1
{
    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() })
     *
     * @param mixed $a
     */
    public function methodA($a)
    {
    }
}
