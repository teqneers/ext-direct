<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.01.16
 * Time: 10:38
 */

namespace TQ\ExtDirect\Tests\Router;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use TQ\ExtDirect\Router\AuthorizationChecker;

/**
 * Class AuthorizationCheckerTest
 *
 * @package TQ\ExtDirect\Tests\Router
 */
class AuthorizationCheckerTest extends TestCase
{
    public function testAuthorizationCheckPassesWithEmptyExpression()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|MockObject $trustResolver */
        $trustResolver = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAuthenticated', 'isRememberMe', 'isFullFledged']
        );

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|MockObject $tokenStorage */
        $tokenStorage = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']
        );

        $tokenStorage->expects($this->once())
                     ->method('getToken')
                     ->willReturn(
                         class_exists(NullToken::class) ? new NullToken() : new AnonymousToken('secret', 'user')
                     );

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|MockObject $authChecker */
        $authChecker = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']
        );

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|MockObject $language */
        $language = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate']
        );

        $language->expects($this->never())
                 ->method('evaluate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|MockObject $service */
        $service = $this->createPartialMock('TQ\ExtDirect\Router\ServiceReference', ['getAuthorizationExpression']);

        $service->expects($this->once())
                ->method('getAuthorizationExpression')
                ->willReturn(null);

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, []));
    }

    public function testAuthorizationCheckReturnsTrueWhenNoAuthenticationTokenIsAvailable()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|MockObject $trustResolver */
        $trustResolver = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAuthenticated', 'isRememberMe', 'isFullFledged']
        );

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|MockObject $tokenStorage */
        $tokenStorage = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']
        );

        $tokenStorage->expects($this->once())
                     ->method('getToken')
                     ->willReturn(null);

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|MockObject $authChecker */
        $authChecker = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']
        );

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|MockObject $language */
        $language = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate']
        );

        $language->expects($this->never())
                 ->method('evaluate');

        /** @var \TQ\ExtDirect\Router\ServiceReference|MockObject $service */
        $service = $this->createPartialMock('TQ\ExtDirect\Router\ServiceReference', ['getAuthorizationExpression']);

        $service->expects($this->never())
                ->method('getAuthorizationExpression');

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, []));
    }

    public function testAuthorizationCheckEvaluatesExpression()
    {
        /** @var \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface|MockObject $trustResolver */
        $trustResolver = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface',
            ['isAuthenticated', 'isRememberMe', 'isFullFledged']
        );

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|MockObject $tokenStorage */
        $tokenStorage = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface',
            ['getToken', 'setToken']
        );

        $token = class_exists(NullToken::class) ? new NullToken() : new AnonymousToken('secret', 'user');
        $tokenStorage->expects($this->exactly(2))
                     ->method('getToken')
                     ->willReturn($token);

        /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|MockObject $authChecker */
        $authChecker = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface',
            ['isGranted']
        );

        /** @var \TQ\ExtDirect\Router\ServiceReference|MockObject $service */
        $service = $this->createPartialMock('TQ\ExtDirect\Router\ServiceReference', ['getAuthorizationExpression']);

        $expression = 'true';
        $arguments  = ['a' => 1, 'b' => 2];

        $service->expects($this->once())
                ->method('getAuthorizationExpression')
                ->willReturn($expression);

        /** @var \Symfony\Component\Security\Core\Authorization\ExpressionLanguage|MockObject $language */
        $language = $this->createPartialMock(
            'Symfony\Component\Security\Core\Authorization\ExpressionLanguage',
            ['evaluate']
        );

        $variables = [
            'token' => $token,
            'user' => $token->getUser(),
            'roles' => [],
            'trust_resolver' => $trustResolver,
            'auth_checker' => $authChecker,
            'args' => $arguments,
        ];

        $language->expects($this->once())
                 ->method('evaluate')
                 ->with($expression, $variables)
                 ->willReturn(true);

        $checker = new AuthorizationChecker($language, $trustResolver, $tokenStorage, $authChecker, null);
        $this->assertEquals(true, $checker->isGranted($service, $arguments));
    }
}
