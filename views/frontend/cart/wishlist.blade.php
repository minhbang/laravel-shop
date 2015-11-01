@extends('layouts.column2')
@section('content')
    <div class="page-compare">
        <h2 class="main-heading text-center">{{trans('shop::cart.wishlist')}}</h2>
        <div class="table-responsive products-table">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td class="min-width">#</td>
						<td class="min-width"></td>
						<td>{{trans('product::common.code_th')}}</td>
						<td>{{trans('product::common.product')}}</td>
                        <td class="min-width">{{trans('product::common.manufacturer_id')}}</td>
                        <td class="min-width">{{trans('product::common.price')}}</td>
                        <td></td>
					</tr>
				</thead>
				<tbody>
                    @foreach($products as $i => $product)
					{!! $product->present()->htmlWishlistRow($i +1) !!}
                    @endforeach
				</tbody>
			</table>
		</div>
        <div class="alert alert-warning text-center" id="wishlist-empty"{!!$products->count() ? 'style="display:none"':'' !!}>
            {!!trans('shop::cart.wishlist_empty')!!}
        </div>
    </div>
@endsection