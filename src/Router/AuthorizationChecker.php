<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 21.01.16
 * Time: 16:58
 */

namespace TQ\ExtDirect\Router;

use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as BaseAuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Class AuthorizationChecker
 *
 * @package TQ\ExtDirect\Router
 */
class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var AuthenticationTrustResolverInterface
     */
    private $trustResolver;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var BaseAuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var RoleHierarchyInterface|null
     */
    private $roleHierarchy;

    /**
     * @param ExpressionLanguage                   $language
     * @param AuthenticationTrustResolverInterface $trustResolver
     * @param TokenStorageInterface                $tokenStorage
     * @param BaseAuthorizationCheckerInterface    $authChecker
     * @param RoleHierarchyInterface|null          $roleHierarchy
     */
    public function __construct(
        ExpressionLanguage $language,
        AuthenticationTrustResolverInterface $trustResolver,
        TokenStorageInterface $tokenStorage,
        BaseAuthorizationCheckerInterface $authChecker,
        RoleHierarchyInterface $roleHierarchy = null
    ) {
        $this->language      = $language;
        $this->trustResolver = $trustResolver;
        $this->tokenStorage  = $tokenStorage;
        $this->authChecker   = $authChecker;
        $this->roleHierarchy = $roleHierarchy;
    }

    /**
     * @param ServiceReference $service
     * @param array            $arguments
     * @return bool
     */
    public function isGranted(ServiceReference $service, array $arguments)
    {
        if ($this->tokenStorage->getToken() === null) {
            return true;
        }


        $authorizationExpression = $service->getAuthorizationExpression();
        if (empty($authorizationExpression)) {
            return true;
        }

        return $this->language->evaluate($authorizationExpression, $this->getVariables($arguments));
    }

    /**
     * @param array $arguments
     * @return array
     */
    private function getVariables(array $arguments)
    {
        $token = $this->tokenStorage->getToken();

        if ($this->roleHierarchy !== null) {
            $roles = $this->roleHierarchy->getReachableRoles($token->getRoles());
        } else {
            $roles = $token->getRoles();
        }

        $variables = array(
            'token'          => $token,
            'user'           => $token->getUser(),
            'roles'          => array_map(function (Role $role) {
                return $role->getRole();
            }, $roles),
            'trust_resolver' => $this->trustResolver,
            'auth_checker'   => $this->authChecker,
            'args'           => $arguments
        );

        return $variables;
    }
}
