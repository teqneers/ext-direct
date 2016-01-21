<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 21.01.16
 * Time: 16:58
 */

namespace TQ\ExtDirect\Router\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TQ\ExtDirect\Router\AuthorizationCheckerInterface;
use TQ\ExtDirect\Router\Event\ServiceResolveEvent;
use TQ\ExtDirect\Router\Exception\NotAuthorizedException;
use TQ\ExtDirect\Router\RouterEvents;

/**
 * Class AuthorizationListener
 *
 * @package TQ\ExtDirect\Router\EventListener
 */
class AuthorizationListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            RouterEvents::AFTER_RESOLVE => array('onAfterResolve', 0)
        );
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param ServiceResolveEvent $event
     */
    public function onAfterResolve(ServiceResolveEvent $event)
    {
        if (!$this->authorizationChecker->isGranted($event->getService(), $event->getArguments())) {
            throw new NotAuthorizedException();
        }
    }
}
