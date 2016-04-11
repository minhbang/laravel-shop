<?php
namespace Minhbang\Shop\Models;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\HasAlias;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Product\Models\Product;

/**
 * Class Order
 *
 * @package Minhbang\Shop\Models
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property boolean $status
 * @property integer $subtotal
 * @property integer $tax
 * @property string $payment_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Product\Models\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereSubtotal($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereTax($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order wherePaymentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Shop\Models\Order searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 * @mixin \Eloquent
 */
class Order extends Model
{
    const STATUS_NEW             = 1;
    const STATUS_CANCELED        = 2;
    const STATUS_PAYMENT_FAILED  = 3;
    const STATUS_PAYMENT_SUCCESS = 4;
    const STATUS_DELIVERED       = 5;

    use PresentableTrait;
    use DatetimeQuery;
    use SearchQuery;
    use HasAlias;
    protected $presenter = 'Minhbang\Shop\Presenters\OrderPresenter';
    protected $table = 'orders';
    protected $fillable = ['name', 'email', 'phone', 'address'];

    /**
     * @return array
     */
    public function aliases()
    {
        return [
            'Status'    => [
                static::STATUS_NEW             => trans('shop::order.status.new'),
                static::STATUS_CANCELED        => trans('shop::order.status.canceled'),
                static::STATUS_PAYMENT_FAILED  => trans('shop::order.status.payment_failed'),
                static::STATUS_PAYMENT_SUCCESS => trans('shop::order.status.payment_success'),
                static::STATUS_DELIVERED       => trans('shop::order.status.delivered'),
            ],
            'StatusCss' => [
                static::STATUS_NEW             => 'success',
                static::STATUS_CANCELED        => 'default',
                static::STATUS_PAYMENT_FAILED  => 'danger',
                static::STATUS_PAYMENT_SUCCESS => 'primary',
                static::STATUS_DELIVERED       => 'info',
            ],
        ];
    }

    /**
     * Danh sách status dùng cho select btngroup
     *
     * @return array
     */
    public function statuses()
    {
        $statuses = $this->itemAlias('Status');
        $csses = $this->itemAlias('StatusCss');
        $result = [];
        foreach ($statuses as $status => $label) {
            $result[] = [
                'value'      => $status,
                'text'       => $label,
                'attributes' => [
                    'data-type' => $csses[$status],
                    'data-url'  => route('backend.order.status', ['order' => $this->id, 'status' => $status]),
                ],
            ];
        }

        return $result;
    }

    /**
     * Sản phẩm thuộc đơn hàng này
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('Minhbang\Product\Models\Product')->withPivot('quantity');
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeQueryDefault($query)
    {
        return $query->select("{$this->table}.*");
    }

    /**
     * @param \Minhbang\Shop\Requests\OrderRequest $request
     * @param array $cart
     * @param string $payment_id
     * @param int $status
     */
    public static function addNew($request, $cart, $payment_id, $status)
    {
        $order = new Order();
        $order->fill($request->all());
        $order->subtotal = $cart['subtotal'];
        $order->tax = $cart['tax'];
        $order->payment_id = $payment_id;
        $order->status = $status;
        $order->save();
        foreach ($cart['items'] as $item) {
            $order->products()->save(Product::find($item['id']), ['quantity' => $item['quantity']]);
        }
    }

    /**
     * @param string $payment_id
     * @param int $status
     */
    public static function updateStatus($payment_id, $status)
    {
        if ($payment_id && ($model = static::findBy('payment_id', $payment_id))) {
            $model->status = $status;
            $model->save();
        }
    }
}
