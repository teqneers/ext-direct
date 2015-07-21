<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TQ\ExtDirect\Router\ArgumentValidatorInterface;
use TQ\ExtDirect\Router\Event\ServiceResolveEvent;
use TQ\ExtDirect\Router\RouterEvents;

/**
 * Class ArgumentValidationListener
 *
 * @package TQ\ExtDirect\Router\EventListener
 */
class ArgumentValidationListener implements EventSubscriberInterface
{
    /**
     * @var ArgumentValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            RouterEvents::AFTER_RESOLVE => array('onAfterResolve', -128)
        );
    }

    /**
     * @param ArgumentValidatorInterface $validator
     */
    public function __construct(ArgumentValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ServiceResolveEvent $event
     */
    public function onAfterResolve(ServiceResolveEvent $event)
    {
        $this->validator->validate($event->getService(), $event->getArguments());
    }
}
