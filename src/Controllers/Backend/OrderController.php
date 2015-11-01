<?php
namespace Minhbang\LaravelShop\Controllers\Backend;

use Minhbang\LaravelKit\Extensions\BackendController as Controller;
use Minhbang\LaravelShop\Models\Order;
use Datatable;
use Html;
use Request;

/**
 * Class OrderController
 *
 * @package Minhbang\LaravelShop\Controllers\Backend
 */
class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct(config('shop.middlewares.backend'));
    }

    /**
     * Danh sách Order theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\LaravelShop\Models\Order $query */
        $query = Order::queryDefault();
        if (Request::has('search_form')) {
            $query->searchWhere('orders.status')
                ->searchWhereBetween('orders.created_at', 'mb_date_vn2mysql');
        }
        return Datatable::query($query)
            ->addColumn(
                'index',
                function (Order $model) {
                    return $model->id;
                }
            )
            ->addColumn(
                'name',
                function (Order $model) {
                    return $model->present()->name;
                }
            )
            ->addColumn(
                'products',
                function (Order $model) {
                    return $model->present()->products;
                }
            )
            ->addColumn(
                'status',
                function (Order $model) {
                    return $model->present()->status_button;
                }
            )
            ->addColumn(
                'actions',
                function (Order $model) {
                    return Html::tableActions(
                        'backend/order',
                        $model->id,
                        $model->name,
                        trans('shop::order.order'),
                        [
                            'renderShow' => 'link',
                            'renderEdit' => false,
                        ]
                    );
                }
            )
            ->searchColumns('orders.name', 'orders.address', 'orders.phone', 'orders.email')
            ->make();
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $tableOptions = [
            'id'        => 'order-manage',
            'class'     => 'table-sortable',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width', 'aTargets' => [0, -1, -2]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '#',
                trans('shop::order.name'),
                trans('shop::order.products'),
                trans('shop::order.statuses'),
                trans('common.actions')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $statuses = (new Order())->itemAlias('Status');
        $this->buildHeading(trans('shop::order.manage'), 'fa-list', ['#' => trans('shop::order.order')]);
        return view('shop::backend.order.index', compact('tableOptions', 'options', 'table', 'statuses'));
    }

    /**
     * Xem chi tiết đơn hàng
     *
     * @param \Minhbang\LaravelShop\Models\Order $order
     *
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $config = config('product.featured_image');
        $products = $order->products;
        $this->buildHeading(
            trans('common.object_details_view', ['name' => trans('shop::order.order')]),
            'fa-list-alt',
            [route('backend.order.index') => trans('shop::order.order'), '#' => trans('common.detail')]
        );
        return view('shop::backend.order.show', compact('order', 'products', 'config'));
    }

    /**
     * Xóa đơn hàng
     *
     * @param \Minhbang\LaravelShop\Models\Order $order
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('shop::order.order')]),
            ]
        );
    }

    /**
     * Cập nhật trạng thái Đơn hàng
     *
     * @param \Minhbang\LaravelShop\Models\Order $order
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Order $order, $status)
    {
        if ($status && $order->itemAlias('Status', $status)) {
            $order->status = $status;
            $order->timestamps = false;
            $order->save();
            return response()->json([
                'type'    => 'success',
                'content' => trans('shop::order.update_status_success'),
            ]);
        } else {
            return response()->json([
                'type'    => 'error',
                'content' => trans('shop::order.invalid_status_value'),
            ]);
        }

    }
}
