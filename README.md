# Simple Role Based Auth extension for Laravel 4

---

A simple Role/Permission based auth package for Laravel4

---

* Roles
* Permissions
* Exceptions

---

## Installation

Just place require new package for your laravel installation via composer.json

    "edvinaskrucas/rbauth": "dev-master"

Then hit ```composer update```

### Registering it in Laravel

Add following lines to ```app/config/app.php```

ServiceProvider array

```php
'Krucas\RBAuth\RBAuthServiceProvider'
```

Change auth driver to ```rbauth``` in ```app/config/auth.php```

Now you are able to use it with Laravel4.

## Config

If you want to use your own implementations of interfaces you need to publish package config file by using ```php artisan config:publish edvinaskrucas/rbauth```
Now you will be able to change default implementations i a file: ```app/config/packages/edvinaskrucas/rbauth/```

## Usage

### Basic examples

Sample RoleInterface and RoleProviderInterface implementations are included, but method ```can($identifier)``` must be implemented by user.

#### Logging in a user

```php
$input = Input::all();

try
{
    Auth::attempt(array('email' => $input['email'], 'password' => $input['password']), isset($input['reminder']));
    return Redirect::back(); // All is ok
}
catch(UserNotFoundException $e)
{
    // User not found
}
catch(UserPasswordIncorrectException $e)
{
    // Password incorrect
}
```

#### Determine if a logged in user is in a role

Returns boolean ```true``` (if has a role assigned) or ```false``` (if has not a role assigned)

```php
Auth::is('admin');
```

#### Determine if a logged in user has permission to a resource

Returns boolean ```true``` (if can) or ```false``` (if can not)

```php
Auth::can('view.profile');
```

### Extending Auth with your custom checks

Sometimes you need to check few rules on a certain object, so you can easily do that by adding your custom checks.
This example shows how to check compound permissions.
For example you have two permissions for editing a trip: ```trips.edit.all``` and ```trips.edit.own```, you can use double check on a certain trip by using simple calls, or you just can use this example below.

```php
Auth::addCheck('editTrip', function($trip)
{
    if(Auth::can('trips.edit.all'))
    {
        return true;
    }
    elseif(Auth::can('trips.edit.own') && $trip->user_id == Auth::user()->id)
    {
        return true;
    }

    return false;
});
```

### Exceptions

This auth extension throws two exceptions when you are trying to login:

```\Krucas\RBAuth\UserNotFoundException``` - thrown when you are trying to login with non existing user.
```\Krucas\RBAuth\PasswordIncorrectException``` - thrown when password for user is incorrect.