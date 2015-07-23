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
 * Class Service4
 *
 * @package TQ\ExtDirect\Tests\Service\Services
 *
 * @Direct\Action()
 */
class Service4
{
    /**
     * @param $a
     * @param $b
     * @param $c
     * @param $d
     */
    public function __construct($a, $b, $c, $d)
    {
    }

    /**
     * @Direct\Method()
     *
     * @param mixed $a
     */
    public function methodA($a)
    {
    }
}
