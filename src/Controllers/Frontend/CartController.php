<?php
namespace Minhbang\LaravelShop\Controllers\Frontend;

use Minhbang\LaravelProduct\Models\Product;
use Minhbang\LaravelShop\Requests\OrderRequest;
use Minhbang\LaravelKit\Extensions\Controller;
use Minhbang\LaravelShop\Models\Order;
use Minhbang\LaravelContent\Content;
use Illuminate\Http\Request;
use Cart;

class CartController extends Controller
{
    /**
     * Xem chi tiết giỏ hàng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $config = config('product.featured_image');
        $this->buildBreadcrumbs(['#' => trans('shop::cart.cart')]);
        return view('shop::frontend.cart.show', Cart::getInfo() + compact('config'));
    }

    /**
     * Thêm $product và giỏ hàng
     * Nếu có trong giỏ hàng rồi thì + dồn quantity
     *
     * @param \Illuminate\Http\Request $request
     * @param \Minhbang\LaravelProduct\Models\Product $product
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, $product)
    {
        $quantity = (int)$request->input('quantity', 1);
        if (Cart::hasProduct($product)) {
            Cart::updateProduct($product, $quantity);
        } else {
            Cart::addProduct($product, $quantity);
        }
        return response()->json(Cart::getInfo());
    }

    /**
     * Bỏ $product ra khỏi Giỏ hàng
     *
     * @param \Minhbang\LaravelProduct\Models\Product $product
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($product)
    {
        Cart::removeProduct($product);
        return response()->json(Cart::getInfo());
    }

    /**
     * Overide quantity của $product trong giỏ hàng
     *
     * @param \Illuminate\Http\Request $request
     * @param \Minhbang\LaravelProduct\Models\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quantity(Request $request, $product)
    {
        $quantity = $request->input('_value');
        if (is_numeric($quantity) && $quantity > 0) {
            $item = Cart::get($product->id);
            if ($item) {
                $calculated = price_format($quantity * $item->price, 'đ', false, true);
                $quantity = $quantity - $item->quantity;
                Cart::updateProduct($product, $quantity);
                return response()->json([
                    'type'    => 'success',
                    'message' => trans('shop::cart.quantity_success'),
                    'result'  => ['calculated' => $calculated] + Cart::getInfo(),
                ]);
            } else {
                return response()->json(['type' => 'error', 'message' => trans('shop::cart.error_product_not_found')]);
            }
        } else {
            return response()->json(['type' => 'error', 'message' => trans('shop::cart.error_quantity_invalid')]);
        }
    }

    /**
     * Thanh toán giỏ hàng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout()
    {
        $order = new Order();
        $terms_conditions = Content::findBy('slug', config('shop.pages.terms_conditions'));
        $this->buildBreadcrumbs([route('cart.show') => trans('shop::cart.cart'), '#' => trans('shop::cart.checkout')]);
        return view('shop::frontend.cart.checkout', compact('order', 'terms_conditions') + Cart::getInfo());
    }

    public function complete(OrderRequest $request)
    {
        if (Cart::isEmpty()) {
            return view('shop::frontend.cart.message', [
                'type'  => 'danger',
                'title' => trans('shop::cart.empty_title'),
                'body'  => trans('shop::cart.empty'),
            ]);
        } else {
            $cart = Cart::getInfo(false);
            $content = Content::findBy('slug', config('shop.pages.order_success'));
            $order = new Order();
            $order->fill($request->all());
            $order->subtotal = $cart['subtotal'];
            $order->tax = $cart['vat'];
            $order->save();
            foreach ($cart['items'] as $item) {
                $order->products()->save(Product::find($item['id']), ['quantity' => $item['quantity']]);
            }
            Cart::clear();
            return view('shop::frontend.cart.message', [
                'type'  => 'success',
                'title' => $content->title,
                'body'  => $content->body,
            ]);
        }
    }
}
