@extends('backend.layouts.main')
@section('content')
<div id="order-manage-tools" class="hidden">
    <div class="dataTables_toolbar">
        {!! Html::linkButton('#', trans('common.search'), ['class'=>'advanced_search_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'search']) !!}
        {!! Html::linkButton('#', trans('common.all'), ['class'=>'filter-clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
    </div>
    <div class="bg-warning dataTables_advanced_search">
        <form class="form-horizontal" role="form">
            {!! Form::hidden('search_form', 1) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('search_created_at', trans('common.created_at'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-9">
                            {!! Form::daterange('search_created_at', [], ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('search_status', trans('shop::order.statuses'), ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-9">
                            {!! Form::select('search_status', $statuses, null, ['prompt' => '', 'class' => 'form-control selectize']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="ibox ibox-table">
    <div class="ibox-title">
        <h5>{!! trans('shop::order.manage_title') !!}</h5>
    </div>
    <div class="ibox-content">
    {!! $table->render('_datatable') !!}
    </div>
</div>
@stop
@section('script')
<script type="text/javascript">
    function datatableDrawCallback(oTable) {
        oTable.find('select.select-btngroup').select_btngroup({
            dataTable: oTable
        });
    };
</script>
    @include(
        '_datatable_script',
        [
            'name' => trans('shop::order.order'),
            'data_url' => route('backend.order.data'),
            'drawCallback' => 'window.datatableDrawCallback'
        ]
    )
@stop