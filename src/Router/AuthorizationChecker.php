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
     * @var ExpressionLanguage|null
     */
    private $language;

    /**
     * @var AuthenticationTrustResolverInterface|null
     */
    private $trustResolver;

    /**
     * @var TokenStorageInterface|null
     */
    private $tokenStorage;

    /**
     * @var BaseAuthorizationCheckerInterface|null
     */
    private $authChecker;

    /**
     * @var RoleHierarchyInterface|null
     */
    private $roleHierarchy;

    /**
     * @param ExpressionLanguage|null                   $language
     * @param AuthenticationTrustResolverInterface|null $trustResolver
     * @param TokenStorageInterface|null                $tokenStorage
     * @param BaseAuthorizationCheckerInterface|null    $authChecker
     * @param RoleHierarchyInterface|null               $roleHierarchy
     */
    public function __construct(
        ExpressionLanguage $language = null,
        AuthenticationTrustResolverInterface $trustResolver = null,
        TokenStorageInterface $tokenStorage = null,
        BaseAuthorizationCheckerInterface $authChecker = null,
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
        if ($this->language === null) {
            throw new \LogicException('To use the @Security annotation, you need to use the Security component 2.4 or newer and to install the ExpressionLanguage component.');
        }
        if ($this->trustResolver === null || $this->tokenStorage === null || $this->authChecker === null) {
            throw new \LogicException('To use the @Security annotation, you need to install the Symfony Security bundle.');
        }
        if ($this->tokenStorage->getToken() === null) {
            throw new \LogicException('To use the @Security annotation, your service needs to be behind a firewall.');
        }

        return $this->language->evaluate('', $this->getVariables());
    }

    /**
     * @return array
     */
    private function getVariables()
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
        );

        return $variables;
    }
}
