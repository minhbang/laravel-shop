@extends('layouts.column2')
@section('content')
    <div class="page-category">
        @if($products->count())
            <div class="main-heading">
                <i class="fa fa-sitemap"></i> {{$category->title}}
            </div>
            @include('shop::frontend._display_options', ['options' => $category_options])
            {!! ShopWidget::productsList($products) !!}
            {!! Html::pagination($products, trans('product::common.product')) !!}
        @else
            <section class="products-list"><h2 class="product-head">{{$category->title}}</h2></section>
            <div class="alert alert-info text-center">{!! trans('shop::common.category_empty') !!}</div>
        @endif
    </div>
@endsection