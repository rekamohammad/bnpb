<div class="b-auth-wrapper">
    {!! Form::open(['route' => 'public.access.register', 'method' => 'post']) !!}
    <div class="form-group @if ($errors->has('username')) has-error @endif">
        <label class="control-label">{{ trans('acl::auth.login.username') }}</label>
        {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => trans('acl::auth.login.username')]) !!}
        {!! Form::error('username', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
        <label for="first_name">{{ __('First Name') }}</label>
        {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
        {!! Form::error('first_name', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('last_name')) has-error @endif">
        <label for="last_name">{{ __('Last Name') }}</label>
        {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
        {!! Form::error('last_name', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('email')) has-error @endif">
        <label for="email">{{ __('Email') }}</label>
        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => __('Email')]) !!}
        {!! Form::error('email', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('password')) has-error @endif">
        <label class="control-label">{{ trans('acl::auth.login.password') }}</label>
        {!! Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('acl::auth.login.password')]) !!}
        {!! Form::error('password', $errors) !!}
    </div>

    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
        <label class="control-label">{{ trans('acl::auth.repassword') }}</label>
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('acl::auth.reset.repassword')]) !!}
        {!! Form::error('password_confirmation', $errors) !!}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
    </div>
    {!! Form::close() !!}
    <p class="link-bottom">{!! __('You have an account already, :link to login', ['link' => anchor_link(route('public.access.login'), __('click here'))]) !!}</p>
</div>