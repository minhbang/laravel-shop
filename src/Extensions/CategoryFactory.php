<?php
namespace Minhbang\LaravelShop\Extensions;

use Minhbang\LaravelCategory\CategoryFactory as BaseFactory;

/**
 * Class CategoryFactory
 *
 * @package App\Exceptions
 */
class CategoryFactory extends BaseFactory
{
    protected function types()
    {
        return [
            'product' => trans('shop::extensions.category.product'),
            'age'     => trans('shop::extensions.category.age'),
        ];
    }


}