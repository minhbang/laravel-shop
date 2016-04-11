<div id="cart" class="btn-group btn-block">
    <button type="button" data-toggle="dropdown" class="btn btn-block btn-lg dropdown-toggle" aria-expanded="true">
        <i class="fa fa-shopping-cart"></i>
        <span class="hidden-sm">{{trans('shop::cart.cart')}}</span>
        <span class="total"><span class="cart-count">{{$count}}</span> {{trans_choice('shop::cart.items', $count)}} - <span class="cart-total">{{$total}}</span><i class="fa fa-caret-down"></i></span>
    </button>
    <ul class="dropdown-menu pull-right">
        <li class="cart-items"{!!$count ? '': ' style="display: none"'!!}>
            <table class="table table-items">
                <tbody>
                @foreach ($items as $item)
                    <tr class="item-row-{{$item->id}}">
                        <td class="min-width">
                            <a href="{{$item->attributes->url}}"><img src="{{$item->attributes->image_small_url}}" alt="{{$item->name}}" title="{{$item->name}}" width="{{$config['width_sm']}}" height="{{$config['height_sm']}}"></a>
                        </td>
                        <td class="text-left">
                            <a href="{{$item->attributes->url}}">{{$item->name}}</a>
                        </td>
                        <td class="min-width text-nowrap">x {{$item->quantity}}</td>
                        <td class="text-right min-width">{{price_format($item->price, config('shop.currency_short'), false, true, config('shop.decimals'))}}</td>
                        <td class="min-width">
                            @if($removable)
                                <a href="{{route('cart.remove', ['product' => $item->id])}}" data-action="cart-remove" data-ref="cart-dropdown" data-id="{{$item->id}}"><i class="fa fa-times"></i></a>
                            @else
                                <span class="text-muted"><i class="fa fa-times"></i></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </li>
        <li class="cart-price"{!!$count ? '': ' style="display: none"'!!}>
            <table class="table table-bordered table-price">
                <tbody>
                <tr>
                    <td class="text-right"><strong>{{trans('shop::cart.subtotal')}}</strong></td>
                    <td><span class="price cart-subtotal">{{$subtotal}}</span></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{{trans('shop::cart.tax_vat')}}</strong></td>
                    <td><span class="price cart-vat">{{$tax}}</span></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{{trans('shop::cart.order_total')}}</strong></td>
                    <td><span class="price cart-total">{{$total}}</span></td>
                </tr>
                </tbody>
            </table>
            <p class="text-right btn-block1">
                <a href="{{route('cart.show')}}">{{trans('shop::cart.show')}}</a><a href="{{route('cart.checkout')}}">{{trans('shop::cart.checkout')}}</a>
            </p>
        </li>
        <li class="cart-empty"{!!$count ? ' style="display: none"':''!!}>
            <p class="text-center">{!!trans('shop::cart.empty')!!}</p>
        </li>
    </ul>

    <div id="cart-template" style="display: none;">
        <table class="item">
            <tr class="item-row-__ID__">
                <td class="min-width">
                    <a href="__URL__"><img data-src="#__IMG_SM__" alt="__NAME__" title="__NAME__" width="{{$config['width_sm']}}" height="{{$config['height_sm']}}"></a>
                </td>
                <td class="text-left"><a href="__URL__">__NAME__</a></td>
                <td class="min-width text-nowrap">x __QUANTITY__</td>
                <td class="text-right min-width">__PRICE__</td>
                <td class="min-width">
                    <a href="{{route('cart.remove', ['product' => '__ID__'])}}" data-action="cart-remove" data-ref="cart-dropdown" data-id="__ID__"><i class="fa fa-times"></i></a>
                </td>
            </tr>
        </table>
        <div class="form">
            <div class="cart-form-modal">
                <div class="row product-info">
                    <div class="col-xs-4">
                        <img class="img-responsive" data-src="#__IMG__" alt="__NAME__" title="__NAME__" width="{{$config['width']}}" height="{{$config['height']}}">
                    </div>
                    <div class="col-xs-8 product-details"><h3 class="name">__NAME__</h3>

                        <div class="price">
                            <span class="price-head">{{trans('product::common.price')}}</span><span class="price-new">__PRICE_NEW__</span><span class="price-old">__PRICE_OLD__</span>
                        </div>
                        <div class="form-horizontal" id="cart-form-__ID__">
                            <div class="form-group">
                                <label for="quantity" class="col-xs-3 control-label text-uppercase">{{trans('shop::cart.quantity')}}</label>

                                <div class="col-xs-4">
                                    <input class="form-control quantity" name="quantity" type="text" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>