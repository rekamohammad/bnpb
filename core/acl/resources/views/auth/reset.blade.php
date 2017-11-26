@extends('acl::auth.master')

@section('content')

    <h3 class="form-title font-green">{{ trans('acl::auth.reset.title') }}</h3>
    <div class="content-wrapper">
        {!! Form::open(['route' => 'access.reset-password']) !!}
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span></span>
            </div>
            <div class="form-group has-feedback">
                <label class="control-label visible-ie8 visible-ie9">{{ trans('acl::auth.reset.new_password') }}</label>
                {!! Form::password('password', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.new_password')]) !!}
                <i class="fa fa-lock form-control-feedback"></i>
            </div>

            <div class="form-group has-feedback">
                <label class="control-label visible-ie8 visible-ie9">{{ trans('acl::auth.repassword') }}</label>
                {!! Form::password('repassword', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.repassword')]) !!}
                <i class="fa fa-lock form-control-feedback"></i>
            </div>

            <div class="row form-actions">
                <div class="col-xs-12">
                    <input type="hidden" name="token" value="{{ $token }}"/>
                    <input type="hidden" name="user" value="{{ $user->username }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-unlock"></i>
                        {{ trans('acl::auth.reset.update') }}
                    </button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

@stop
