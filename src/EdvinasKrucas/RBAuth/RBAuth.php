<?php namespace EdvinasKrucas\RBAuth;

use EdvinasKrucas\RBAuth\Exception\UserNotFoundException;
use EdvinasKrucas\RBAuth\Exception\UserPasswordIncorrectException;
use Illuminate\Auth\Guard;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;
use EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface;
use Illuminate\Config\Repository;
use EdvinasKrucas\RBAuth\Contracts\UserInterface;

class RBAuth extends Guard
{
    /**
     * Role provider implementation.
     *
     * @var \EdvinasKrucas\RBAuth\Contracts\RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * Config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @param UserProviderInterface $provider
     * @param SessionStore $session
     * @param RoleProviderInterface $roleProvider
     * @param Repository $config
     */
    public function __construct(UserProviderInterface $provider,
                                SessionStore $session,
                                RoleProviderInterface $roleProvider,
                                Repository $config)
    {
        parent::__construct($provider, $session);
        $this->roleProvider = $roleProvider;
        $this->config = $config;
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
            if($this->user()->can($this->config->get('rbauth::super_permission')))
            {
                return true;
            }
            return $this->user()->can($identifier);
        }
        else
        {
            $role = $this->roleProvider->getByName($this->config->get('rbauth::default_role'));

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
            return $this->user()->is($roleName);
        }

        return false;
    }

    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return bool
     * @throws \EdvinasKrucas\RBAuth\Exception\UserPasswordIncorrectException
     * @throws \EdvinasKrucas\RBAuth\Exception\UserNotFoundException
     */
    public function attempt(array $credentials = array(), $remember = false, $login = true)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($user instanceof UserInterface)
        {
            if ($this->provider->validateCredentials($user, $credentials))
            {
                if ($login) $this->login($user, $remember);

                return true;
            }
            else
            {
                throw new UserPasswordIncorrectException();
            }
        }
        else
        {
            throw new UserNotFoundException();
        }

        return false;
    }
}