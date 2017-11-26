@extends('acl::auth.master')

@section('content')
    <h3 class="form-title font-green">{{ trans('acl::auth.login_title') }}</h3>
    <div class="content-wrapper">
        {!! Form::open(['route' => 'access.login', 'class' => 'login-form']) !!}
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span></span>
            </div>
            <div class="form-group">
                <label class="control-label">{{ trans('acl::auth.login.username') }}</label>
                {!! Form::text('username', old('username', app()->environment('demo') ? 'botble' : null), ['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('acl::auth.login.username')]) !!}
            </div>

            <div class="form-group">
                <label class="control-label">{{ trans('acl::auth.login.password') }}</label>
                {!! Form::input('password', 'password', (app()->environment('demo') ? '159357' : null), ['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('acl::auth.login.password')]) !!}
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox checkbox-primary">
                            <label class="check mt-checkbox mt-checkbox-outline">
                                {!! Form::checkbox('remember', '1', null, ['class' => 'styled']) !!} {{ trans('acl::auth.login.remember') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-6 text-right">
                        <a class="lost-pass-link" href="{{ route('access.forgot-password') }}" title="{{ trans('acl::auth.forgot_password.title') }}">{{ trans('acl::auth.lost_your_password') }}</a>
                    </div>
                </div>
            </div>

            <div class="form-group form-actions">
                <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> {{ trans('acl::auth.login.login') }}</button>
            </div>

            <div class="login-options">
                <h4>{{ trans('acl::auth.login_via_social') }}</h4>
                <ul class="social-icons">
                    <li>
                        <a class="social-icon-color facebook" data-original-title="facebook" href="{{ route('auth.social', 'facebook') }}"></a>
                    </li>
                    <li>
                        <a class="social-icon-color googleplus" data-original-title="Google Plus" href="{{ route('auth.social', 'google') }}"></a>
                    </li>
                    <li>
                        <a class="social-icon-color github" data-original-title="Github" href="{{ route('auth.social', 'github') }}"></a>
                    </li>
                </ul>
            </div>

        {!! Form::close() !!}
    </div>
@stop
