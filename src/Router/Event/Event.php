<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Router\Event;

if (class_exists(\Symfony\Contracts\EventDispatcher\Event::class)) {
    abstract class Event extends \Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    abstract class Event extends \Symfony\Component\EventDispatcher\Event
    {
    }
}
