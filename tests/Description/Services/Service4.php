<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.07.15
 * Time: 12:14
 */

namespace TQ\ExtDirect\Tests\Description\Services;

use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service4
 *
 * @package TQ\ExtDirect\Tests\Description\Services
 *
 * @Direct\Action()
 */
class Service4
{
    /**
     * @Direct\Method()
     */
    public function methodA()
    {
    }

    /**
     * @Direct\Method(true)
     */
    public function methodB()
    {
    }
}
