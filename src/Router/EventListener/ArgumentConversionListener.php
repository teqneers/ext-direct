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
use TQ\ExtDirect\Router\ArgumentConverterInterface;
use TQ\ExtDirect\Router\Event\ServiceResolveEvent;
use TQ\ExtDirect\Router\RouterEvents;

/**
 * Class ArgumentConversionListener
 *
 * @package TQ\ExtDirect\Router\EventListener
 */
class ArgumentConversionListener implements EventSubscriberInterface
{
    /**
     * @var ArgumentConverterInterface
     */
    private $converter;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            RouterEvents::AFTER_RESOLVE => array('onAfterResolve', 128)
        );
    }

    /**
     * @param ArgumentConverterInterface $converter
     */
    public function __construct(ArgumentConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param ServiceResolveEvent $event
     */
    public function onAfterResolve(ServiceResolveEvent $event)
    {
        $event->setArguments(
            $this->converter->convert(
                $event->getService(),
                $event->getArguments())
        );
    }
}
