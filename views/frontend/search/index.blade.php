@extends('layouts.column2')
@section('content')
    <div class="product-search">
        <div class="main-heading">
            <i class="fa fa-search text-success"></i> {{trans('shop::common.search_result')}}:
            @if($total)
                <small class="text-danger">{{$total}} {{trans('product::common.product')}}</small>
            @endif
        </div>
        <div class="form search">

            {!! Form::model($params, ['id'=>'search-form', 'route' => 'search', 'method' => 'get']) !!}
            <div class="input-group">
                {!! Form::text('q', null, ['id' => 'q', 'class' => 'query input-lg form-control', 'placeholder' => trans('common.keyword').'...']) !!}
                <span class="input-group-btn">
                    <button class="btn btn-success btn-lg" type="submit">
                        <i class="fa fa-search"></i> {{trans('common.search')}}
                    </button>
                    <a id="btn-search-advanced" href="#search-advanced" class="btn btn-default btn-lg" role="button"
                       data-toggle="collapse" aria-expanded="{{ $advanced ? 'true':'false' }}"
                       aria-controls="search-advanced">
                        {{trans('common.advanced')}} <i class="fa"></i>
                    </a>
                </span>
            </div>
            <div class="collapse {{ $advanced ? ' in':'' }}" id="search-advanced"
                 aria-expanded="{{ $advanced ?'true':'false' }}">
                <div class="form-group">
                    {!! Form::label('category_id', trans('category::common.category'), ['class' => 'control-label']) !!}
                    {!! Form::select($column_key['category_id'], $categories, null, ['prompt' =>trans('common.all'), 'class' => 'form-control selectize-tree']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('manufacturer_id', trans('product::manufacturer.manufacturer'), ['class' => 'control-label']) !!}
                    {!! Form::select($column_key['manufacturer_id'].'[]', $manufacturers, null, ['multiple', 'prompt' =>trans('common.all'), 'class' => 'form-control selectize']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('gender_id', trans('product::common.gender_id'), ['class' => 'control-label']) !!}
                    {!! Form::select($column_key['gender_id'].'[]', $genders, null, ['multiple', 'prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('age_id', trans('product::common.age_id'), ['class' => 'control-label']) !!}
                    {!! Form::select($column_key['age_id'].'[]', $ages, null, ['multiple', 'prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        @if($total)
            @include('shop::frontend._display_options', ['options' => $search_options])
            {!! ShopWidget::productsList($products) !!}
            {!! Html::pagination($products, trans('product::common.product'), $params) !!}
        @else
            <div class="alert alert-danger text-center">{{trans('shop::common.search_empty')}}</div>
        @endif
    </div>
@stop

@section('script')
    <script type="text/javascript">
        var collapse_icon = $('#btn-search-advanced').find('.fa'),
                collapse = $('#search-advanced');
        function updateCollapseIcon() {
            if (collapse.hasClass('in')) {
                collapse_icon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
            } else {
                collapse_icon.removeClass("fa-chevron-up").addClass("fa-chevron-down");
            }
        }
        collapse.on('shown.bs.collapse', function () {
            updateCollapseIcon();
        }).on('hidden.bs.collapse', function () {
            updateCollapseIcon();
        });
        updateCollapseIcon();
    </script>
@stop