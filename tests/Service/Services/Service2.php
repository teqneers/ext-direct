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
 * Class Service2
 *
 * @package TQ\ExtDirect\Tests\Service\Services
 *
 * @Direct\Action()
 */
class Service2
{
    /**
     * @Direct\Method()
     *
     * @param mixed $a
     */
    public function methodA($a)
    {
    }
}
