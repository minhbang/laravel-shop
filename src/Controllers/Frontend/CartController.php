<?php
namespace Minhbang\Shop\Controllers\Frontend;

use Minhbang\Shop\Extensions\Paypal;
use Minhbang\Shop\Requests\OrderRequest;
use Minhbang\Kit\Extensions\Controller;
use Minhbang\Shop\Models\Order;
use Minhbang\Content\Content;
use Illuminate\Http\Request;
use Cart;
use Redirect;

class CartController extends Controller
{
    /**
     * @var Paypal
     */
    protected $payment;

    /**
     * CartController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->payment = new Paypal();
    }

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
     * @param \Minhbang\Product\Models\Product $product
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
     * @param \Minhbang\Product\Models\Product $product
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
     * @param \Minhbang\Product\Models\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quantity(Request $request, $product)
    {
        $quantity = $request->input('_value');
        if (is_numeric($quantity) && $quantity > 0) {
            $item = Cart::get($product->id);
            if ($item) {
                $calculated = price_format($quantity * $item->price, config('shop.currency_short'), false, true, config('shop.decimals'));
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
     * Xem thông tin thanh toán giỏ hàng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout()
    {
        $order = new Order();
        $terms_conditions = Content::findBySlug(config('shop.pages.terms_conditions'));
        $this->buildBreadcrumbs([route('cart.show') => trans('shop::cart.cart'), '#' => trans('shop::cart.checkout')]);

        return view('shop::frontend.cart.checkout', compact('order', 'terms_conditions') + Cart::getInfo());
    }

    /**
     * Thanh toán paypal + Lưu thông tin giao dịch đặt hàng
     *
     * @param \Minhbang\Shop\Requests\OrderRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            list($payment_id, $redirect_url) = $this->payment->checkout($cart, route('cart.status'), route('cart.cancel'));
            if ($redirect_url) {
                $status = Order::STATUS_NEW;
                $response = Redirect::away($redirect_url);
            } else {
                $status = Order::STATUS_PAYMENT_FAILED;
                $response = $this->showMessage('payment_failed', 'error');
            }
            Order::addNew($request, $cart, $payment_id, $status);

            return $response;
        }
    }

    /**
     * Được Paypal gọi
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(Request $request)
    {
        $result = $this->payment->getResult($request);
        $payment_id = $this->payment->getId();
        $this->payment->forgetId();
        Cart::clear();

        if ($result->getState() == 'approved') {
            Order::updateStatus($payment_id, Order::STATUS_PAYMENT_SUCCESS);

            return $this->showMessage('payment_success', 'success');
        }

        Order::updateStatus($payment_id, Order::STATUS_PAYMENT_FAILED);

        return $this->showMessage('payment_failed', 'error');
    }

    /**
     * Cancel cart checkout
     *
     * @return mixed
     */
    public function cancel()
    {
        $payment_id = $this->payment->getId();
        $this->payment->forgetId();
        Order::updateStatus($payment_id, Order::STATUS_CANCELED);

        return $this->showMessage('payment_canceled', 'warning');
    }

    /**
     * @param string $content
     * @param string $type
     *
     * @return mixed
     */
    protected function showMessage($content, $type)
    {
        $content = Content::findBySlug(config("shop.pages.{$content}"));

        return view('shop::frontend.cart.message', [
            'type'  => $type,
            'title' => $content->title,
            'body'  => $content->body,
        ]);
    }
}
