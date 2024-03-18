<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Metadata\Driver\Services\Sub;

use TQ\ExtDirect\Annotation as Direct;
use TQ\ExtDirect\Tests\Metadata\Driver\Services\Service4;

/**
 * Class Service3
 *
 * @package TQ\ExtDirect\Tests\Metadata\Driver\Services
 *
 * @Direct\Action("app.direct.test")
 */
#[Direct\Action("app.direct.test")]
class Service6 extends Service4
{
    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    public function methodA()
    {
    }
}
