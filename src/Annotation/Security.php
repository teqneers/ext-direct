<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.01.16
 * Time: 10:18
 */

namespace TQ\ExtDirect\Annotation;

use Doctrine\Common\Annotations\NamedArgumentConstructor;

/**
 * Class Security
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target({"CLASS","METHOD"})
 * @NamedArgumentConstructor
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Security
{
    public function __construct(public string $expression) {}
}
