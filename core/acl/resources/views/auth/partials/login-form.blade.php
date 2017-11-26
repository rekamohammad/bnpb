<div class="b-auth-wrapper">
    {!! Form::open(['route' => 'public.access.login', 'method' => 'post']) !!}
    <div class="form-group @if ($errors->has('username')) has-error @endif">
        <label class="control-label">{{ trans('acl::auth.login.username') }}</label>
        {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => trans('acl::auth.login.username')]) !!}
        {!! Form::error('username', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('password')) has-error @endif">
        <label class="control-label">{{ trans('acl::auth.login.password') }}</label>
        {!! Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('acl::auth.login.password')]) !!}
        {!! Form::error('password', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('remember')) has-error @endif">
        <div class="row">
            <div class="col-xs-6">
                <input type="checkbox" name="remember" id="remember"> <label for="remember">{{ trans('acl::auth.login.remember') }}</label>
                {!! Form::error('remember', $errors) !!}
            </div>
            <div class="col-xs-6 text-right">
                <a class="lost-pass-link" href="{{ route('public.access.forgot-password') }}" title="{{ trans('acl::auth.forgot_password.title') }}">{{ trans('acl::auth.lost_your_password') }}</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('acl::auth.login.login') }}</button>
    </div>
    {!! Form::close() !!}
    <p class="link-bottom">{!! __('Or :link', ['link' => anchor_link(route('public.access.register'), __('register new account'))]) !!}</p>
</div>