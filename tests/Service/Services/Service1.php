<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Service\Services;

use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service1
 *
 * @package TQ\ExtDirect\Tests\Service\Services
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
    public function methodA(mixed $a)
    {
    }
}
