@extends('backend.layouts.main')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="ibox ibox-table">
            <div class="ibox-title">
                <h5>{!! trans('shop::order.price') !!}</h5>
            </div>
            <div class="ibox-content">
                <table class="table table-hover table-striped table-bordered table-detail">
                    <tr>
                        <td>{{ trans('shop::cart.subtotal') }}</td>
                        <td class="text-right"><strong>{{ $order->present()->subtotal}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::cart.tax_vat') }}</td>
                        <td class="text-right"><strong>{{ $order->present()->tax}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::cart.order_total') }}</td>
                        <td class="text-right"><strong>{{ $order->present()->total}}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="ibox ibox-table">
            <div class="ibox-title">
                <h5>{!! trans('shop::order.statuses') !!}</h5>
            </div>
            <div class="ibox-content">
                <table class="table table-hover table-striped table-bordered table-detail">
                    <tr>
                        <td style="vertical-align: middle">{{ trans('shop::order.statuses') }}</td>
                        <td class="text-right">
                            {!! $order->present()->status !!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox ibox-table">
            <div class="ibox-title">
                <h5>{!! trans('shop::order.info') !!}</h5>
            </div>
            <div class="ibox-content">
                <table class="table table-hover table-striped table-bordered table-detail">
                    <tr>
                        <td>ID</td>
                        <td><strong>{{ $order->id}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::order.name') }}</td>
                        <td><strong>{{ $order->name}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::order.email') }}</td>
                        <td><strong>{{ $order->email}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::order.phone') }}</td>
                        <td><strong>{{ $order->phone}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('shop::order.address') }}</td>
                        <td><strong>{{ $order->address}}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ trans('common.created_at') }}</td>
                        <td>{!! $order->present()->createdAt !!}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('common.updated_at') }}</td>
                        <td>{!! $order->present()->updatedAt !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="ibox ibox-table">
    <div class="ibox-title">
        <h5>{!! trans('shop::order.products') !!}</h5>
    </div>
    <div class="ibox-content">
        <div class="table-responsive products-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="min-width">#</th>
                        <th class="min-width"></th>
                        <th>{{trans('product::common.code_th')}}</th>
                        <th>{{trans('product::common.product')}}</th>
                        <th class="min-width">{{trans('shop::cart.quantity_th')}}</th>
                        <th class="min-width">{{trans('product::common.price')}}</th>
                        <th class="min-width">{{trans('shop::cart.price')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $i => $product)
                <tr>
                    <td class="min-width text-right">{{$i+1}}</td>
                    <td class="min-width column-image">
                        <a href="{{route('backend.product.show', ['product' => $product->id])}}"><img src="{{$product->featured_image_sm_url}}" width="{{$config['width_sm']}}" height="{{$config['height_sm']}}"></a>
                    </td>
                    <td class="min-width">{{$product->code}}</td>
                    <td><a href="{{route('backend.product.show', ['product' => $product->id])}}">{{$product->name}}</a></td>
                    <td class="min-width">{{$product->pivot->quantity}}</td>
                    <td class="min-width text-right">{{price_format($product->price, config('shop.currency_short'), false, true, config('shop.decimals'))}}</td>
                    <td class="min-width text-right">{{price_format($product->price * $product->pivot->quantity, config('shop.currency_short'), false, true, config('shop.decimals'))}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

    </div>
        </div>
@stop