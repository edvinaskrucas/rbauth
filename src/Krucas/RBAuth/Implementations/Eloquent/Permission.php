<?php namespace Krucas\RBAuth\Implementations\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * Table to store permissions.
     *
     * @var string
     */
    protected $table = 'permissions';
}