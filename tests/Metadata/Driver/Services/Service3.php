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
 * Class Service3
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action("app.direct.test")
 */
#[Direct\Action("app.direct.test")]
class Service3
{
    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    public function methodA()
    {
    }
}
