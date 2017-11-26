<div class="b-auth-wrapper">
    {!! Form::open(['route' => 'public.access.reset-password', 'method' => 'post']) !!}
    <div class="form-group @if ($errors->has('password')) has-error @endif">
            <label class="control-label">{{ trans('acl::auth.reset.new_password') }}</label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('acl::auth.reset.new_password')]) !!}
            {!! Form::error('password', $errors) !!}
        </div>

        <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
            <label class="control-label">{{ trans('acl::auth.repassword') }}</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('acl::auth.reset.repassword')]) !!}
            {!! Form::error('password_confirmation', $errors) !!}
        </div>

        <div class="row form-actions">
            <div class="col-xs-6">
                <input type="hidden" name="token" value="{{ $token }}"/>
                <input type="hidden" name="user" value="{{ $user->username }}">
            </div>
            <div class="col-xs-6">
                <button type="submit" class="btn btn-warning pull-right">
                    {{ trans('acl::auth.reset.update') }}
                </button>
            </div>
        </div>
    {!! Form::close() !!}
</div>