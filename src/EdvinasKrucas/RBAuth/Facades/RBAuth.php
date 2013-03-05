<?php namespace EdvinasKrucas\RBAuth\Facades;

use Illuminate\Support\Facades\Facade;

class RBAuth extends  Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rbauth';
    }
}