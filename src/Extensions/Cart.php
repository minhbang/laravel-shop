<?php
namespace Minhbang\Shop\Extensions;

use Darryldecode\Cart\Cart as BaseCart;

class Cart extends BaseCart
{
    /**
     * Lấy thông tin hiện tại về cart
     *
     * @param bool $format
     *
     * @return array
     */
    public function getInfo($format = true)
    {
        $items = $this->getContent()->sortBy('price')->values();
        $count = $items->count();
        $total = $this->getTotal();
        $subtotal = $this->getSubTotal();
        $vat = $count && config('shop.vat') ? $this->getCondition('VAT')->getCalculatedValue($subtotal) : null;
        if ($format) {
            $total = price_format($total, 'đ', false, true);
            $subtotal = price_format($subtotal, 'đ', false, true);
            $vat = price_format($vat, 'đ', false, true);
        }
        return compact('items', 'count', 'total', 'subtotal', 'vat');
    }

    /**
     * @param \Minhbang\Product\Models\Product $product
     *
     * @return bool
     */
    public function hasProduct($product)
    {
        return $this->has($product->id);
    }

    /**
     * @param \Minhbang\Product\Models\Product $product
     */
    public function removeProduct($product)
    {
        $this->remove($product->id);
    }

    /**
     * @param \Minhbang\Product\Models\Product $product
     * @param int $quantity
     */
    public function addProduct($product, $quantity = 1)
    {
        $this->add(
            [
                'id'         => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => $quantity,
                'attributes' => [
                    'code'            => $product->code,
                    'url'             => $product->url,
                    'image_url'       => $product->featured_image_url,
                    'image_small_url' => $product->featured_image_sm_url,
                ],
            ]
        );
    }

    /**
     * @param \Minhbang\Product\Models\Product $product
     * @param int $quantity
     */
    public function updateProduct($product, $quantity)
    {
        $this->update($product->id, ['quantity' => $quantity]);
    }
}