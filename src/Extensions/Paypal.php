<?php
namespace Minhbang\Shop\Extensions;

use PayPal\Api\PaymentExecution;
use Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;

/**
 * Class Paypal
 *
 * @package Minhbang\Shop\Extensions
 */
class Paypal
{
    /**
     * @var \PayPal\Rest\ApiContext
     */
    protected $api_context;

    /**
     * Paypal constructor.
     */
    public function __construct()
    {
        $this->api_context = new ApiContext(
            new OAuthTokenCredential(config('shop.paypal.client_id'), config('shop.paypal.secret'))
        );
        $this->api_context->setConfig(config('shop.paypal.config'));
    }

    /**
     * Get the session payment ID
     *
     * @return string
     */
    public function getId()
    {
        return Session::get('paypal_payment_id');
    }

    /**
     * Clear the session payment ID
     */
    public function forgetId()
    {
        Session::forget('paypal_payment_id');
    }

    /**
     * When the payment successfully made, it will return this 2 parameters as query string
     * token=EC-05R25178G5276364N
     * PayerID=LXA67A9A83UD6
     * Otherwise, ONLY token when the customer cancel the payment
     * token=EC-05R25178G5276364N
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \PayPal\Api\Payment
     */
    public function getResult($request)
    {
        $payer_id = $request->get('PayerID');
        $token = $request->get('token');

        if (empty($payer_id) || empty($token)) {
            return null;
        }
        $payment_id = $this->getId();
        $payment = Payment::get($payment_id, $this->api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);

        //Execute the payment
        return $payment->execute($execution, $this->api_context);
    }

    /**
     * Thực hiện thanh toán paypal,
     * Trả về [payment_id, redirect_url]
     *
     * @param array $cart
     * @param string $return_url
     * @param string $cancel_url
     * @param string $currency
     *
     * @return string|null
     *
     */
    public function checkout($cart, $return_url, $cancel_url, $currency = 'USD')
    {
        $payment = $this->newPayment($cart, $return_url, $cancel_url, $currency);
        try {
            $payment->create($this->api_context);
        } catch (PayPalConnectionException $ex) {
            if (config('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                //$err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }
        $payment_id = $payment->getId();
        // add payment ID to session
        Session::put('paypal_payment_id', $payment_id);

        return [$payment_id, $this->getRedirectUrl($payment)];
    }

    /**
     * Lấy thanh toán paypal
     */
    public function status()
    {

    }

    /**
     * @param array $cart
     *
     * @param string $currency
     *
     * @return \PayPal\Api\ItemList
     */
    protected function newItemList($cart, $currency)
    {
        $items = [];
        foreach ($cart['items'] as $product) {
            $items[] = $this->newItem($product, $currency);
        }
        $item_list = new ItemList();
        $item_list->setItems($items);

        return $item_list;
    }

    /**
     * @param array $data
     * @param string $currency
     *
     * @return \PayPal\Api\Item
     */
    protected function newItem($data, $currency)
    {
        $item = new Item();
        $item->setName($data['name'])
            ->setCurrency($currency)
            ->setQuantity($data['quantity'])
            ->setPrice($data['price']);

        return $item;
    }

    /**
     * @param array $cart
     * @param string $currency
     *
     * @return \PayPal\Api\Amount;
     */
    protected function newAmount($cart, $currency)
    {
        $amount = new Amount();
        $amount->setCurrency($currency)->setTotal($cart['total']);

        return $amount;
    }

    /**
     * @param array $cart
     *
     * @param string $currency
     *
     * @return \PayPal\Api\Transaction
     */
    protected function newTransaction($cart, $currency)
    {
        $transaction = new Transaction();
        $transaction
            ->setAmount($this->newAmount($cart, $currency))
            ->setItemList($this->newItemList($cart, $currency))
            ->setDescription('Your transaction description');

        return $transaction;
    }

    /**
     * @param array $cart
     * @param string $return_url
     * @param string $cancel_url
     * @param string $currency
     *
     * @return \PayPal\Api\Payment
     */
    protected function newPayment($cart, $return_url, $cancel_url, $currency)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($return_url)->setCancelUrl($cancel_url);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions([$this->newTransaction($cart, $currency)]);

        return $payment;
    }

    /**
     * @param \PayPal\Api\Payment $payment
     *
     * @return null|string
     */
    protected function getRedirectUrl($payment)
    {
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return $link->getHref();
            }
        }

        return null;
    }
}