@extends('layouts.column2')
@section('content')
    <div class="page-compare">
        <h2 class="main-heading text-center">{{trans('shop::cart.compare_products')}}</h2>
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
                    <tr><td colspan="7" class="text-success heading">{{trans('shop::cart.selected_product')}}</td></tr>
                    {!! $product->present()->htmlWishlistRow() !!}
                    <tr><td colspan="7" class="text-success heading">{{trans('shop::cart.wishlist')}}</td></tr>
                    @if(count($products))
                        @foreach($products as $i => $p)
                            {!! $p->present()->htmlWishlistRow($i +1) !!}
                        @endforeach
                    @else
                        <tr><td colspan="7" class="text-danger text-center empty">{!!trans('shop::cart.wishlist_empty')!!}</td></tr>
                    @endif
				</tbody>
			</table>
		</div>
    </div>
@endsection