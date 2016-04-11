@extends('layouts.column2')
@section('content')
    <div class="page-checkout">
        <h2 class="main-heading text-center">{{trans('shop::cart.checkout')}}</h2>
		@if($count)
			{!! Form::model($order, ['url' => route('cart.complete'), 'class' => 'form form-horizontal']) !!}
            <div class="row">
				<div class="col-md-5">
					<div class="panel panel-smart panel-total">
						<div class="panel-heading">
							<h3 class="panel-title">{{trans('shop::cart.price')}}</h3>
						</div>
						<div class="panel-body">
							<dl class="dl-horizontal">
								<dt>{{trans('shop::cart.count')}} :</dt>
								<dd>{!!$count!!}</dd>
								<dt>{{trans('shop::cart.subtotal')}} :</dt>
								<dd>{!!$subtotal!!}</dd>
								<dt>{{trans('shop::cart.tax_vat')}} :</dt>
								<dd>{!!$tax!!}</dd>
							</dl>
							<hr>
							<dl class="dl-horizontal total">
								<dt>{{trans('shop::cart.order_total')}} :</dt>
								<dd>{!!$total!!}</dd>
							</dl>

						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="panel panel-smart">
						<div class="panel-heading">
							<h3 class="panel-title">{{trans('shop::cart.shipment_information')}}</h3>
						</div>
						<div class="panel-body">
							<div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
								{!! Form::label('name', trans('shop::order.name'), ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-xs-9">
									{!! Form::text('name', null, ['placeholder' => trans('shop::order.name'), 'class' => 'form-control']) !!}
									@if($errors->has('name'))
										<p class="help-block">{{ $errors->first('name') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
								{!! Form::label('email', trans('shop::order.email'), ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-xs-9">
									{!! Form::text('email', null, ['placeholder' => trans('shop::order.email'), 'class' => 'form-control']) !!}
									@if($errors->has('email'))
										<p class="help-block">{{ $errors->first('email') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('phone') ? ' has-error':'' }}">
								{!! Form::label('phone', trans('shop::order.phone'), ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-xs-9">
									{!! Form::text('phone', null, ['placeholder' => trans('shop::order.phone'), 'class' => 'form-control']) !!}
									@if($errors->has('phone'))
										<p class="help-block">{{ $errors->first('phone') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('address') ? ' has-error':'' }}">
								{!! Form::label('address', trans('shop::order.address'), ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-xs-9">
									{!! Form::text('address', null, ['placeholder' => trans('shop::order.address'), 'class' => 'form-control']) !!}
									@if($errors->has('address'))
										<p class="help-block">{{ $errors->first('address') }}</p>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
			@if($terms_conditions)
				<div class="panel panel-smart">
					<div class="panel-heading">
						<h3 class="panel-title">{{$terms_conditions->title}}</h3>
					</div>
					<div class="panel-body">
						{!! $terms_conditions->body !!}
					</div>
				</div>
			@endif
			<hr>
			<div class="clearfix">
				<a href="{{route('cart.show')}}" class="btn btn-success pull-left"><i class="fa fa-arrow-left"></i> {{trans('common.back')}}</a>
				<button type="submit" class="btn btn-danger pull-right">{{trans('shop::cart.checkout')}} <i class="fa fa-credit-card"></i></button>
			</div>
			{!! Form::close() !!}
        @else
            <div class="alert alert-warning text-center">{!!trans('shop::cart.empty')!!}</div>
        @endif
    </div>
@endsection