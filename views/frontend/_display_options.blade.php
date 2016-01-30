<div class="display-options">
    <div class="form form-inline">
        <div class="buttons">
            {!! $options->link('type', 'th', 'th', trans('shop::common.display_th')) !!}
            {!! $options->link('type', 'list', 'list', trans('shop::common.display_list')) !!}
        </div>
        <div class="pull-right">
            <div class="form-group">
                {!! $options->select('sort', trans('shop::common.sort'), trans('shop::common.sort_hint')) !!}
            </div>
            <div class="form-group">
                {!! $options->select('page_size', trans('shop::common.page_size'), trans('shop::common.page_size_hint')) !!}
            </div>
        </div>
    </div>
</div>