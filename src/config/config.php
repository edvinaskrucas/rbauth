<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | Default user provider
     |--------------------------------------------------------------------------
     |
     | User provider interface implementation.
     |
     */
    'user_model'            => 'User',

    /*
     |--------------------------------------------------------------------------
     | Default role provider.
     |--------------------------------------------------------------------------
     |
     | Default role provider implementation.
     |
     */
    'role_provider'         => 'Role',

    /*
     |--------------------------------------------------------------------------
     | Default role name
     |--------------------------------------------------------------------------
     |
     | Default role will be used when user is logged out.
     |
     */
    'default_role'          => 'guest',

    /*
     |--------------------------------------------------------------------------
     | Super permission
     |--------------------------------------------------------------------------
     |
     | Permission name that overrides all other permissions (root access).
     |
     */
    'super_permission'      => 'root'

);