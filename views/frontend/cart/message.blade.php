@extends('layouts.column2')
@section('content')
    <div class="content-show text-{{$type}}">
        <div class="page-header">
            <h1 class="main-heading">{{$title}}</h1>
        </div>
        <div class="content">
            {!!$body!!}
        </div>
    </div>
@stop
