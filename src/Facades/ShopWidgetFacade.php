<?php
namespace Minhbang\Shop\Facades;

use Illuminate\Support\Facades\Facade;

class ShopWidgetFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shopWidget';
    }
}