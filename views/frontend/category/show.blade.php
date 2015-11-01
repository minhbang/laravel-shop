@extends('layouts.column2')
@section('content')
    <div class="page-category">
        @if($products->count())
            {!! $category->present()->htmlProductsList($products) !!}
            {!! Html::pagination($products, trans('product::common.product')) !!}
        @else
            <section class="products-list"><h2 class="product-head">{{$category->title}}</h2></section>
            <div class="alert alert-info text-center">{!! trans('shop::extensions.category_empty') !!}</div>
        @endif
    </div>
@endsection