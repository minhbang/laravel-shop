<?php
/**
 * Shop routes
 */
Route::group(
    ['namespace' => 'Minhbang\Shop\Controllers\Frontend', 'middleware' => config('shop.middlewares.frontend')],
    function () {
        Route::get('/', 'HomeController@index');
        // Category
        Route::group(
            ['prefix' => 'category'],
            function () {
                Route::get('/', ['as' => 'category.index', 'uses' => 'CategoryController@index']);
                Route::get('{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
            }
        );
        // Cart
        Route::group(
            ['prefix' => 'cart', 'as' => 'cart.'],
            function () {
                Route::post('add/{product}', ['as' => 'add', 'uses' => 'CartController@add']);
                Route::post('quantity/{product}', ['as' => 'quantity', 'uses' => 'CartController@quantity']);
                Route::delete('remove/{product}', ['as' => 'remove', 'uses' => 'CartController@remove']);
                Route::get('show', ['as' => 'show', 'uses' => 'CartController@show']);
                Route::get('checkout', ['as' => 'checkout', 'uses' => 'CartController@checkout']);
                Route::post('complete', ['as' => 'complete', 'uses' => 'CartController@complete']);
                Route::get('status', ['as' => 'status', 'uses' => 'CartController@status']);
                Route::get('cancel', ['as' => 'cancel', 'uses' => 'CartController@cancel']);
            }
        );

        // Wishlist
        Route::group(
            ['prefix' => 'wishlist', 'as' => 'wishlist.'],
            function () {
                Route::get('count', ['as' => 'count', 'uses' => 'WishlistController@count']);
                Route::post('update/{product}', ['as' => 'update', 'uses' => 'WishlistController@update']);
                Route::get('compare/{product}', ['as' => 'compare', 'uses' => 'WishlistController@compare']);
                Route::get('show', ['as' => 'show', 'uses' => 'WishlistController@show']);
            }
        );
        // Search
        Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
    }
);

Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Shop\Controllers\Backend', 'middleware' => config('shop.middlewares.backend')],
    function () {
        // Order
        Route::group(['prefix' => 'order', 'as' => 'backend.order.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'OrderController@index']);
            Route::get('data', ['as' => 'data', 'uses' => 'OrderController@data']);
            Route::get('{order}', ['as' => 'show', 'uses' => 'OrderController@show']);
            Route::post('{order}/{status}', ['as' => 'status', 'uses' => 'OrderController@status']);
            Route::delete('{order}', ['as' => 'destroy', 'uses' => 'OrderController@destroy']);
        });
    }
);
