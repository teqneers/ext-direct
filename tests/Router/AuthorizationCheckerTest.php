<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.01.16
 * Time: 10:38
 */

namespace TQ\ExtDirect\Tests\Router;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use TQ\ExtDirect\Router\AuthorizationChecker;

/**
 * Class AuthorizationCheckerTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class AuthorizationCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthorizationCheckPassesWithEmptyExpression()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|\PHPUnit_Framework_MockObject_MockObject $trustResolver */
        $trustResolver = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAnonymous', 'isRememberMe', 'isFullFledged']);

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']);

        $tokenStorage->expects($this->once())
                     ->method('getToken')
                     ->willReturn(new AnonymousToken('secret', 'user'));

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject $authChecker */
        $authChecker = $this->getMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']);

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject $language */
        $language = $this->getMock('Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate'], [], '', false);

        $language->expects($this->never())
                 ->method('evaluate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $service->expects($this->once())
                ->method('getAuthorizationExpression')
                ->willReturn(null);

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, []));
    }

    public function testAuthorizationCheckReturnsTrueWhenNoAuthenticationTokenIsAvailable()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|\PHPUnit_Framework_MockObject_MockObject $trustResolver */
        $trustResolver = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAnonymous', 'isRememberMe', 'isFullFledged']);

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']);

        $tokenStorage->expects($this->once())
                     ->method('getToken')
                     ->willReturn(null);

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject $authChecker */
        $authChecker = $this->getMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']);

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject $language */
        $language = $this->getMock('Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate'], [], '', false);

        $language->expects($this->never())
                 ->method('evaluate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $service->expects($this->never())
                ->method('getAuthorizationExpression');

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, []));
    }

    public function testAuthorizationCheckEvaluatesExpression()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|\PHPUnit_Framework_MockObject_MockObject $trustResolver */
        $trustResolver = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAnonymous', 'isRememberMe', 'isFullFledged']);

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']);

        $token = new AnonymousToken('secret', 'user');
        $tokenStorage->expects($this->exactly(2))
                     ->method('getToken')
                     ->willReturn($token);

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject $authChecker */
        $authChecker = $this->getMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']);

        /** @var \TQ\ExtDirect\Router\ServiceReference|\PHPUnit_Framework_MockObject_MockObject $service */
        $service = $this->getMock('TQ\ExtDirect\Router\ServiceReference', [], [], '', false);

        $expression = 'true';
        $arguments  = ['a' => 1, 'b' => 2];

        $service->expects($this->once())
                ->method('getAuthorizationExpression')
                ->willReturn($expression);

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject $language */
        $language = $this->getMock('Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate'], [], '', false);

        $variables = [
            'token'          => $token,
            'user'           => 'user',
            'roles'          => [],
            'trust_resolver' => $trustResolver,
            'auth_checker'   => $authChecker,
            'args'           => $arguments
        ];

        $language->expects($this->once())
                 ->method('evaluate')
                 ->with($expression, $variables)
                 ->willReturn(true);

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, $arguments));
    }
}
