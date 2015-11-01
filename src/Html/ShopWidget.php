<?php
namespace Minhbang\LaravelShop\Html;

use Form;
use Cart;
use Route;
use Category;
use Minhbang\LaravelProduct\Models\Product;
use Minhbang\LaravelProduct\Models\Manufacturer;

/**
 * Class ShopWidget
 *
 * @package Minhbang\LaravelShop\Html
 */
class ShopWidget
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelProduct\Models\Product[] $products
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
        /** @var \Minhbang\LaravelCategory\CategoryItem[] $categories */
        $categories = Category::of('product')->getRoots();
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
        //Todo: option take limit special product
        /** @var \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelProduct\Models\Product[] $products */
        $products = Product::orderPosition()->special()->take(5)->get();
        if ($products->count()) {
            //Todo: route xem toàn bộ sản phẩm specials
            $heading = '<a href="#"><h3 class="sidebar-heading">' . trans('product::common.specials') . '</h3></a>';
            $list = '';
            foreach ($products as $product) {
                $list .= "<li>
                        <div class=\"clearfix\">
                            {$product->present()->featured_image('', true)}
                            <div class=\"price\">
                                {$product->present()->price_old('đ', 'price-old')}
                                {$product->present()->price('đ', 'price-new')}
                            </div>
						</div>
						<h5><a href=\"{$product->url}\">{$product->name}</a></h5>
					</li>";
            }
            return "{$heading}<ul class=\"products-list\">{$list}</ul>";
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function sidebarShoppingOptions()
    {
        $heading = '<h3 class="sidebar-heading">' . trans('product::common.product_options') . '</h3>';
        $options = [
            'manufacturers' => ['title' => trans('product::manufacturer.manufacturer'), 'items' => Manufacturer::getList()],
            'genders'       => ['title' => trans('product::common.genders'), 'items' => (new Product())->itemAlias('Gender')],
            'ages'          => ['title' => trans('shop::extensions.category.age'), 'items' => Category::of('age')->getListRoots()],
        ];
        $html = '<div class="list-group">';
        foreach ($options as $name => $option) {
            $html .= "<div class=\"list-group-item title\">{$option['title']}</div>";
            $filter = '';
            foreach ($option['items'] as $value => $label) {
                $filter .= "<label class=\"checkbox\">
								<input name=\"{$name}[]\" type=\"checkbox\" value=\"{$value}\">
								{$label}
							</label>";
            }
            $html .= "<div class=\"list-group-item\"><div class=\"filter-group\">$filter</div></div>";
        }
        $html .= '<div class="list-group-item actions"><button type="submit" class="btn btn-success">
            <i class="fa fa-filter"></i> ' . trans('product::common.filter') . '</button></div>';
        $html .= '</div>';
        $html = "<div class=\"product-options\">$html</div>";
        return Form::open(['route' => 'backend.dashboard']) . $heading . $html . Form::close();
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