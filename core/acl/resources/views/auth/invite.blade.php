@extends('acl::auth.master')

@section('content')

    <h3 class="form-title font-green">{{ trans('acl::auth.accept_invite') }}</h3>
    <div class="content-wrapper">
            @if (isset($error_msg))
                <div class="alert alert-danger">
                    <p>{{ $error_msg }}</p>
                </div>
            @else
                {!! Form::open(['route' => ['invite.accept', $token]]) !!}

                <div class="form-group @if ($errors->has('username')) has-error @endif">
                    <label for="username" class="control-label">{{ trans('acl::users.username') }}</label>
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'id' => 'username', 'placeholder' => trans('acl::users.username')]) !!}
                </div>
                <div class="form-group has-feedback">
                    <label>{{ trans('acl::users.new_password') }}</label>
                    {!! Form::password('password', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.new-password')]) !!}
                    <i class="icon icon-lock form-control-feedback"></i>
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('acl::users.confirm_new_password') }}</label>
                    {!! Form::password('password_confirmation', ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('acl::auth.reset.repassword')]) !!}
                    <i class="icon icon-lock form-control-feedback"></i>
                </div>

                <div class="row form-actions">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-sign-in"></i>
                            {{ trans('acl::users.save') }}
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
    </div>

@stop
