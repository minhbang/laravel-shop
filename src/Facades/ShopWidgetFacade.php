<?php
namespace Minhbang\LaravelShop\Facades;

use Illuminate\Support\Facades\Facade;

class ShopWidgetFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shopWidget';
    }
}