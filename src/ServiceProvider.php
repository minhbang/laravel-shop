<?php

namespace Minhbang\Shop;

use Illuminate\Routing\Router;
use Darryldecode\Cart\CartCondition;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Minhbang\Shop\Extensions\Cart;
use Minhbang\Shop\Html\ShopWidget;
use Minhbang\Shop\Facades\CartFacade;
use Minhbang\Shop\Facades\WishlistFacade;
use Minhbang\Shop\Facades\ShopWidgetFacade;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\Shop
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'shop');
        $this->loadViewsFrom(__DIR__ . '/../views', 'shop');
        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path('resources/views/vendor/shop'),
                __DIR__ . '/../lang'            => base_path('resources/lang/vendor/shop'),
                __DIR__ . '/../config/shop.php' => config_path('shop.php'),
            ]
        );

        if (config('shop.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        if ($vat = config('shop.vat')) {
            $this->app['cart']->condition(new CartCondition(
                [
                    'name'   => 'VAT',
                    'type'   => 'tax',
                    'target' => 'subtotal',
                    'value'  => $vat,
                ]
            ));
        }
        // pattern filters
        $router->pattern('order', '[0-9]+');
        // model bindings
        $router->model('order', 'Minhbang\Shop\Models\Order');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/shop.php', 'shop');
        $this->app['cart'] = $this->app->share(
            function ($app) {
                return new Cart(
                    $app['session'],
                    $app['events'],
                    'cart',
                    '4yTlTDKu3oJOfzD'
                );
            }
        );

        $this->app['wishlist'] = $this->app->share(
            function ($app) {
                return new Cart(
                    $app['session'],
                    $app['events'],
                    'wishlist',
                    '4yTlTDKu3oJOfzDwl'
                );
            }
        );
        $this->app['shopWidget'] = $this->app->share(
            function () {
                return new ShopWidget();
            }
        );
        // add aliases
        $this->app->booting(
            function () {
                AliasLoader::getInstance()->alias('Cart', CartFacade::class);
                AliasLoader::getInstance()->alias('Wishlist', WishlistFacade::class);
                AliasLoader::getInstance()->alias('ShopWidget', ShopWidgetFacade::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cart', 'wishlist', 'shopWidget'];
    }
}
