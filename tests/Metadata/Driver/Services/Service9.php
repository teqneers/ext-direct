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
 * Class Service9
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action()
 */
class Service9
{
    /**
     * @Direct\Method()
     * @Direct\Parameter("a", { @Assert\NotNull() }, strict=true)
     *
     * @param string $a
     */
    public function methodA($a)
    {
    }
}
