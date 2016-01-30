<?php
namespace Minhbang\Shop;

use Minhbang\Option\Option;

/**
 * Class DisplayOption
 *
 * @package Minhbang\Shop
 */
class DisplayOption extends Option
{
    /**
     * @param string $group
     *
     * @return array
     */
    protected function config($group)
    {
        return config("shop.options.{$group}", []);
    }

    /**
     * @return array
     */
    protected function all()
    {
        return [
            'sort'      => [
                'name.asc'     => trans('shop::common.sort_name') . trans('shop::common.sort_str_asc'),
                'name.desc'    => trans('shop::common.sort_name') . trans('shop::common.sort_str_desc'),
                'price.asc'    => trans('shop::common.sort_price') . trans('shop::common.sort_int_asc'),
                'price.desc'   => trans('shop::common.sort_price') . trans('shop::common.sort_int_desc'),
                'updated.asc'  => trans('shop::common.sort_updated') . trans('shop::common.sort_time_asc'),
                'updated.desc' => trans('shop::common.sort_updated') . trans('shop::common.sort_time_desc'),
            ],
            'page_size' => [6 => 6, 12 => 12, 30 => 30, 60 => 60],
        ];
    }

    /**
     * Chuyển đổi từ giá trị của option 'sort' thành tên column
     *
     * @return array
     */
    protected function columns()
    {
        return [
            'sort' => [
                'name'    => 'products.name',
                'price'   => 'products.price',
                'updated' => 'products.updated_at',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'sort'      => '/^(name|price|updated)\.(asc|desc)$/',
            'page_size' => '/^[\d]+$/',
            'type'      => '/^(th|list)$/',
        ];
    }
}