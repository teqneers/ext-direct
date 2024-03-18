<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 17:30
 */

namespace TQ\ExtDirect\Tests\Service\Services;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use TQ\ExtDirect\Annotation as Direct;

/**
 * Class Service3
 *
 * @package TQ\ExtDirect\Tests\Service\Services
 *
 * @Direct\Action()
 */
#[Direct\Action()]
class Service3 implements ContainerAwareInterface
{
    protected ?ContainerInterface $container;

    /**
     * @Direct\Method()
     */
    #[Direct\Method()]
    public function methodA(mixed $a)
    {
    }

    public function setContainer(?ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
