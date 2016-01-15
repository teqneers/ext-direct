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
use TQ\ExtDirect\Router\Event\InvokeEvent;
use TQ\ExtDirect\Router\ResultConverterInterface;
use TQ\ExtDirect\Router\RouterEvents;

/**
 * Class ResultConversionListener
 *
 * @package TQ\ExtDirect\Router\EventListener
 */
class ResultConversionListener implements EventSubscriberInterface
{
    /**
     * @var ResultConverterInterface
     */
    private $converter;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            RouterEvents::AFTER_INVOKE => array('onAfterInvoke', -128)
        );
    }

    /**
     * @param ResultConverterInterface $converter
     */
    public function __construct(ResultConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param InvokeEvent $event
     */
    public function onAfterInvoke(InvokeEvent $event)
    {
        $event->setResult($this->converter->convert($event->getService(), $event->getResult()));
    }
}
