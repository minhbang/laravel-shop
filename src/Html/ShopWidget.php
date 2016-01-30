<?php
namespace Minhbang\Shop\Html;

use Form;
use Cart;
use Route;
use Minhbang\Product\Models\Product;
use Minhbang\Product\Models\Manufacturer;

/**
 * Class ShopWidget
 *
 * @package Minhbang\Shop\Html
 */
class ShopWidget
{
    /** @var  \Minhbang\Category\Manager */
    protected $categoryManager;

    /**
     * ShopWidget constructor.
     */
    public function __construct()
    {
        $this->categoryManager = app('category')->manage('product');
    }

    /**
     * @param \Minhbang\Category\Item $category
     * @param int $limit
     *
     * @return null|string
     */
    public function productsTop($category, $limit = 6)
    {
        return $category ? $this->productsList(Product::topOf($category, $limit), $category->title) : null;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|\Minhbang\Product\Models\Product[] $products
     * @param mixed $heading
     *
     * @return null|string
     */
    public function productsList($products, $heading = false)
    {
        if ($products->count()) {
            $heading = $heading ? "<h2 class=\"product-head\">{$heading}</h2>" : '';
            $list = '';
            foreach ($products as $product) {
                $list .= "<div class=\"col-md-4 col-sm-6\">{$product->present()->htmlCol}</div>";
            }

            return "<section class=\"products-list\">{$heading}<div class=\"row\">{$list}</div></section>";
        } else {
            return null;
        }
    }

    public function sidebarCategories()
    {
        /** @var \Minhbang\Category\Item[] $categories */
        $categories = $this->categoryManager->roots();
        $html = '';
        foreach ($categories as $category) {
            $html .= "<a href=\"{$category->url}\" class=\"list-group-item\"><i class=\"fa fa-chevron-right\"></i>{$category->title}</a>";
        }
        $html = "<div class=\"list-group categories\">$html</div>";

        return '<h3 class="sidebar-heading">' . trans('category::common.category') . '</h3>' . $html;
    }

    /**
     * @return string
     */
    public function sidebarSpecialProduct()
    {
        /** @var \Illuminate\Database\Eloquent\Collection|\Minhbang\Product\Models\Product[] $products */
        $products = Product::orderPosition()->special()->take(5)->get();
        if ($products->count()) {
            //Todo: route xem toàn bộ sản phẩm specials
            $heading = '<a href="#"><h3 class="sidebar-heading">' . trans('product::common.specials') . '</h3></a>';
            $list = '';
            foreach ($products as $product) {
                $list .= "<li>
                        <a href=\"{$product->url}\">
                        <div class=\"clearfix\">
                            {$product->present()->featured_image('', true)}
                            <div class=\"price\">
                                {$product->present()->price_old('đ', 'price-old')}
                                {$product->present()->price('đ', 'price-new')}
                            </div>
						</div>
						<h5>{$product->name}</h5>
						</a>
					</li>";
            }

            return "{$heading}<ul class=\"products-list\">{$list}</ul>";
        } else {
            return null;
        }
    }

    /**
     * Lọc sản phẩm
     *
     * @return string
     */
    public function sidebarShoppingOptions()
    {
        $heading = '<h3 class="sidebar-heading">' . trans('product::common.product_options') . '</h3>';
        $enums = (new Product())->loadEnums('id');
        $column_key = array_combine(
            array_values(Product::$searchable_keys),
            array_keys(Product::$searchable_keys)
        );
        $options = [
            'manufacturer_id' => ['title' => trans('product::manufacturer.manufacturer'), 'items' => Manufacturer::getList()],
            'gender_id'       => ['title' => trans('product::common.gender_id'), 'items' => $enums['genders']],
            'age_id'          => ['title' => trans('product::common.age_id'), 'items' => $enums['ages']],
        ];
        $html = '<div class="list-group">';
        foreach ($options as $name => $option) {
            $html .= "<div class=\"list-group-item title\">{$option['title']}</div>";
            $filter = '';
            foreach ($option['items'] as $value => $label) {
                $key = $column_key[$name];
                $id = "{$key}_{$value}";
                $filter .= "<div class=\"checkbox checkbox-success\">
<input id=\"$id\" name=\"{$key}[]\" type=\"checkbox\" value=\"{$value}\">
<label for=\"$id\">{$label}</label>
</div>";
            }
            $html .= "<div class=\"list-group-item\"><div class=\"filter-group\">$filter</div></div>";
        }
        $html .= '<div class="list-group-item actions"><button type="submit" class="btn btn-success">
            <i class="fa fa-filter"></i> ' . trans('product::common.filter') . '</button></div>';
        $html .= '</div>';
        $html = "<div class=\"product-options\">$html</div>";

        return Form::open(['route' => 'search', 'method' => 'get']) . $heading . $html . Form::close();
    }

    /**
     * @return string
     */
    public function cartButton()
    {
        $config = config('product.featured_image');
        $removable = Route::currentRouteName() !== 'cart.checkout';

        return view('shop::frontend.cart._cart', Cart::getInfo() + compact('removable', 'config'));
    }
}