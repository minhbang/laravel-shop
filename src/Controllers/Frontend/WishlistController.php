<?php
namespace Minhbang\LaravelShop\Controllers\Frontend;

use Minhbang\LaravelKit\Extensions\Controller;
use Minhbang\LaravelProduct\Models\Product;
use Wishlist;

/**
 * Class WishlistController
 *
 * @package Minhbang\LaravelShop\Controllers\Frontend
 */
class WishlistController extends Controller
{
    /**
     * Get wishlist count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        return response()->json(Wishlist::getContent()->count());
    }

    /**
     * So sánh $product với danh sách Wishlist
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $ids = Wishlist::getContent()->lists('id');
        $products = Product::whereIn('id', $ids)->orderBy('price')->get();
        $this->buildBreadcrumbs(['#' => trans('shop::cart.wishlist')]);
        return view('shop::frontend.cart.wishlist', compact('products'));
    }

    /**
     * So sánh $product với danh sách Wishlist
     *
     * @param \Minhbang\LaravelProduct\Models\Product $product
     *
     * @return \Illuminate\View\View
     */
    public function compare($product)
    {
        $ids = Wishlist::getContent()->lists('id')->all();
        if ($ids) {
            $ids = array_diff($ids, [$product->id]);
            $products = Product::whereIn('id', $ids)->orderBy('price')->get()->all();
        } else {
            $products = [];
        }
        $this->buildBreadcrumbs(['#' => trans('shop::cart.compare_products')]);
        return view('shop::frontend.cart.compare', compact('product', 'products'));
    }

    /**
     * Add/Remove $product => danh sách Wishlist
     *
     * @param \Minhbang\LaravelProduct\Models\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($product)
    {
        $remove = Wishlist::has($product->id);
        if ($remove) {
            Wishlist::removeProduct($product);
        } else {
            Wishlist::addProduct($product);
        }
        return response()->json(['remove' => $remove, 'count' => Wishlist::getContent()->count()]);
    }
}
