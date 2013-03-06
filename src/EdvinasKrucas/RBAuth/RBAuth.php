<?php namespace EdvinasKrucas\RBAuth;

use Illuminate\Auth\Guard;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;
use EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface;

class RBAuth extends Guard
{
    /**
     * Role provider implementation.
     *
     * @var Contracts\RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * Name of default role.
     *
     * @var string
     */
    protected $defaultRoleName;

    /**
     * Create new RBAuth instance.
     *
     * @param UserProviderInterface $provider
     * @param SessionStore $session
     * @param Contracts\RoleProviderInterface $roleProvider
     */
    public function __construct(UserProviderInterface $provider,
                                SessionStore $session,
                                RoleProviderInterface $roleProvider,
                                $defaultRoleName)
    {
        parent::__construct($provider, $session);
        $this->roleProvider = $roleProvider;
        $this->defaultRoleName = $defaultRoleName;
    }

    /**
     * Determines if a user has certain permission.
     * If user is not logged then checks for role permission.
     *
     * @param $identifier
     * @return bool
     */
    public function can($identifier)
    {
        if(!is_null($this->user()))
        {
            return $this->user()->can($identifier);
        }
        else
        {
            $role = $this->roleProvider->getByName($this->defaultRoleName);

            if($role)
            {
                return $role->can($identifier);
            }
        }

        return false;
    }

    /**
     * Determines if a user has a given role.
     *
     * @param $roleName
     * @return bool
     */
    public function is($roleName)
    {
        if(!is_null($this->user()))
        {
            foreach($this->user()->getRoles() as $role)
            {
                if($role->getRoleName() == $roleName)
                {
                    return true;
                }
            }
        }

        return false;
    }
}