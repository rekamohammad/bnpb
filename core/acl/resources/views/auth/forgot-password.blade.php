@extends('acl::auth.master')

@section('content')
    <h3 class="form-title font-green">{{ trans('acl::auth.forgot_password.title') }}</h3>
    <div class="content-wrapper">
        {!! Form::open(['route' => 'access.forgot-password', 'class' => 'forget-form']) !!}
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span></span>
            </div>
            <p>{!! trans('acl::auth.forgot_password.message') !!}</p>
            <div class="form-group">
                {!! Form::text('username', old('username'), ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.login.placeholder.username')]) !!}
            </div>
            <div class="form-group form-actions">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> {{ trans('acl::auth.forgot_password.submit') }}</button>
            </div>
        {!! Form::close() !!}
        <p class="link-bottom"><a href="{{ route('access.login') }}">{{ trans('acl::auth.back_to_login') }}</a></p>
    </div>
@stop
