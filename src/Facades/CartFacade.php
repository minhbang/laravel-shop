<?php
namespace Minhbang\LaravelShop\Facades;

use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}