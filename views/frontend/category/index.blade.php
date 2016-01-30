@extends('layouts.column2')
@section('content')
    <div class="page-category">
        @foreach($categories as $category)
            {!! ShopWidget::productsTop($category) !!}
        @endforeach
    </div>
@endsection