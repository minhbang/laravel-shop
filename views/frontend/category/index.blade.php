@extends('layouts.column2')
@section('content')
    <div class="page-category">
        @foreach($categories as $category)
            {!! $category->present()->htmlProductsTop !!}
        @endforeach
    </div>
@endsection