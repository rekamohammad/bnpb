<div class="b-auth-wrapper">
    {!! Form::open(['route' => 'public.access.forgot-password', 'method' => 'post']) !!}
        <p>{!! trans('acl::auth.forgot_password.message') !!}</p>
        <div class="form-group @if ($errors->has('username')) has-error @endif">
            <label class="control-label">{{ trans('acl::auth.login.username') }}</label>
            {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => trans('acl::auth.login.placeholder.username')]) !!}
            {!! Form::error('username', $errors) !!}
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ trans('acl::auth.forgot_password.submit') }}</button>
        </div>
    {!! Form::close() !!}
    <p class="link-bottom"><a href="{{ route('public.access.login') }}">{{ trans('acl::auth.back_to_login') }}</a></p>
</div>