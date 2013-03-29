<?php namespace Krucas\RBAuth;

use Krucas\RBAuth\Exception\UserNotFoundException;
use Krucas\RBAuth\Exception\UserPasswordIncorrectException;
use Illuminate\Auth\Guard;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;
use Krucas\RBAuth\Contracts\RoleProviderInterface;
use Illuminate\Config\Repository;
use Krucas\RBAuth\Contracts\UserInterface;
use Closure;

class RBAuth extends Guard
{
    /**
     * Role provider implementation.
     *
     * @var \Krucas\RBAuth\Contracts\RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * Config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * User registered custom checks.
     *
     * @var array
     */
    protected $customChecks = array();

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
     * @throws \Krucas\RBAuth\Exception\UserPasswordIncorrectException
     * @throws \Krucas\RBAuth\Exception\UserNotFoundException
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

    /**
     * Adds custom checker.
     *
     * @param $alias
     * @param callable $callback
     */
    public function addCheck($alias, Closure $callback)
    {
        $this->customChecks['can'.ucfirst($alias)] = $callback;
    }

    /**
     * Tries to call custom check.
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        if(strlen($name) > 3 && substr($name, 0, 3) == 'can')
        {
            if(isset($this->customChecks[$name]))
            {
                switch(count($args))
                {
                    case 0: return call_user_func($this->customChecks[$name]);
                    case 1: return call_user_func($this->customChecks[$name], $args[0]);
                    case 2: return call_user_func($this->customChecks[$name], $args[0], $args[1]);
                    case 3: return call_user_func($this->customChecks[$name], $args[0], $args[1], $args[2]);
                    case 4: return call_user_func($this->customChecks[$name], $args[0], $args[1], $args[2], $args[3]);
                    case 5: return call_user_func($this->customChecks[$name], $args[0], $args[1], $args[2], $args[3], $args[4]);
                }

                return call_user_func($this->customChecks[$name], $args);
            }
        }
    }
}