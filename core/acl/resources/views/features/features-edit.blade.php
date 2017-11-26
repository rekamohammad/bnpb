@extends('bases::layouts.master')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark">{{ trans('acl::feature.edit') }}</span>
            </div>
        </div>
        <div class="portlet-body" style="padding: 0;">
            {!! Form::open(['route' => ['system.feature.edit']]) !!}
                <div>
                    @include('acl::features.available-features')
                </div>
                <div class="form-actions text-center">
                    <a class="btn btn-default" href="{{ route('system.feature.list') }}">{{ trans('acl::feature.cancel') }}</a>
                    <input type="submit" value="{{ trans('acl::feature.save') }}" class="btn btn-primary">
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
