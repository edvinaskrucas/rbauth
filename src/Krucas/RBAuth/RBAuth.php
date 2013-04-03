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
     * User registered/overridden rules.
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Ignore callback when calling Auth::can().
     *
     * @var bool
     */
    protected $ignoreCallback = false;

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
     * @param null $arg0
     * @param null $arg1
     * @param null $arg2
     * @param null $arg3
     * @param null $arg4
     * @return bool
     */
    public function can($identifier, $arg0 = null, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        if(!is_null($this->user()))
        {
            if($this->user()->can($this->config->get('rbauth::super_permission')))
            {
                return true;
            }

            if(!$this->ignoreCallback)
            {
                $this->ignoreCallback = false;

                if(isset($this->rules[$identifier]))
                {
                    return $this->callCallback($identifier, $arg0, $arg1, $arg2, $arg3, $arg4);
                }
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
     * Adds new rule for a closure check.
     *
     * @param $permission
     * @param callable $callback
     */
    public function rule($permission, Closure $callback)
    {
        $this->rules[$permission] = $callback;
    }

    /**
     * Sets ignoreCallback to true for a next Auth::can() call.
     *
     * @return \Krucas\RBAuth\RBAuth
     */
    public function ignoreCallback()
    {
        $this->ignoreCallback = true;

        return $this;
    }

    /**
     * Calls a custom registered rule.
     *
     * @param $identifier
     * @param null $arg0
     * @param null $arg1
     * @param null $arg2
     * @param null $arg3
     * @param null $arg4
     * @return bool
     */
    protected function callCallback($identifier, $arg0 = null, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        $callback = $this->rules[$identifier];

        if(!is_null($arg4))
        {
            return call_user_func($callback, $arg0, $arg1, $arg2, $arg3, $arg4);
        }
        elseif(!is_null($arg3))
        {
            return call_user_func($callback, $arg0, $arg1, $arg2, $arg3);
        }
        elseif(!is_null($arg2))
        {
            return call_user_func($callback, $arg0, $arg1, $arg2);
        }
        elseif(!is_null($arg1))
        {
            return call_user_func($callback, $arg0, $arg1);
        }
        elseif(!is_null($arg0))
        {
            return call_user_func($callback, $arg0);
        }
        else
        {
            return call_user_func($callback);
        }

        return false;
    }
}