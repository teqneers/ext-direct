<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.01.16
 * Time: 10:18
 */

namespace TQ\ExtDirect\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Security
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Security
{
    /**
     * @Required
     *
     * @var string
     */
    public $expression;
}
