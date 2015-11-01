@extends('layouts.column2')
@section('content')
    <div class="page-cart">
        <h2 class="main-heading text-center">{{trans('shop::cart.currently_cart')}}</h2>
		@if($count)
            <div class="cart-items">
                <div class="table-responsive products-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="min-width">#</td>
                                <td class="min-width"></td>
                                <td>{{trans('product::common.code_th')}}</td>
                                <td>{{trans('product::common.product')}}</td>
                                <td class="min-width">{{trans('shop::cart.quantity_th')}}</td>
                                <td class="min-width">{{trans('product::common.price')}}</td>
                                <td class="min-width">{{trans('shop::cart.price')}}</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i => $item)
                            <tr id="row-{{$i+1}}" class="item-row-{{$item->id}}">
                                <td class="min-width text-right">{{$i+1}}</td>
                                <td class="min-width">
                                    <a href="{{$item->attributes->url}}"><img src="{{$item->attributes->image_small_url}}" alt="{{$item->name}}" title="{{$item->name}}" width="{{$config['width_sm']}}" height="{{$config['height_sm']}}"></a>
                                </td>
                                <td class="min-width">{{$item->attributes->code}}</td>
                                <td class="name"><a href="{{$item->attributes->url}}">{{$item->name}}</a></td>
                                <td class="min-width quantity">
                                    {!! Html::linkQuickUpdate(
                                        $item->id,
                                        $item->quantity,
                                        [
                                            'attr'      => 'quantity',
                                            'title'     => trans("shop::cart.quantity"),
                                            'placement' => 'top',
                                            'class'     => 'w-xs',
                                        ],
                                        ['class' => 'quick-update-text']
                                    ) !!}
                                </td>
                                <td class="min-width price">{{price_format($item->price, 'đ', false, true)}}</td>
                                <td class="min-width price calculated">{{price_format($item->price * $item->quantity, 'đ', false, true)}}</td>
                                <td class="min-width text-center"><a href="{{route('cart.remove', ['product' => $item->id])}}" data-action="cart-remove" data-ref="cart-show"><i class="fa fa-times"></i></a></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-right"><strong>{{trans('shop::cart.subtotal')}}</strong></td>
                                <td class="price cart-subtotal">{!!$subtotal!!}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><strong>{{trans('shop::cart.tax_vat')}}</strong></td>
                                <td class="price cart-vat">{!!$vat!!}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><strong>{{trans('shop::cart.order_total')}}</strong></td>
                                <td class="price cart-total">{!!$total!!}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="actions clearfix">
                    <a href="{{url('/')}}" class="btn btn-success pull-left"><i class="fa fa-arrow-left"></i> {{trans('shop::cart.continue_shopping')}}</a>
                    <a href="{{route('cart.checkout')}}" class="btn btn-danger pull-right">{{trans('shop::cart.checkout')}} <i class="fa fa-credit-card"></i></a>
                </div>
            </div>
        @endif
        <div class="alert alert-warning text-center cart-empty"{!!$count ? ' style="display: none"':''!!}>{!!trans('shop::cart.empty')!!}</div>
    </div>
@endsection