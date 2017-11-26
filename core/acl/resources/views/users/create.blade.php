@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
    @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, USER_MODULE_SCREEN_NAME, request(), null) @endphp
    <div class="row">
        <div class="col-md-9">
            <div class="main-form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                <label for="first_name" class="control-label required">First Name</label>
                                {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'id' => 'first_name', 'data-counter' => 30]) !!}
                                {!! Form::error('first_name', $errors) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                                <label for="last_name" class="control-label required">Last Name</label>
                                {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'id' => 'last_name', 'data-counter' => 30]) !!}
                                {!! Form::error('last_name', $errors) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('username')) has-error @endif">
                                <label for="username" class="control-label required">Username</label>
                                {!! Form::text('username', old('username'), ['class' => 'form-control', 'id' => 'username', 'data-counter' => 30]) !!}
                                {!! Form::error('username', $errors) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('email')) has-error @endif">
                                <label for="email" class="control-label required">Email</label>
                                {!! Form::text('email', old('email'), ['class' => 'form-control', 'id' => 'email', 'data-counter' => 30]) !!}
                                {!! Form::error('email', $errors) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label required" for="password">Password</label>
                                {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'data-counter' => 60]) !!}
                                <div class="pwstrength_viewport_progress"></div>
                                {!! Form::error('password', $errors) !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label required" for="password_confirmation">Re-type password</label>
                                {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'data-counter' => 60]) !!}
                                {!! Form::error('password_confirmation', $errors) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label required" for="role">Role</label>
                        {!! Form::select('role_id', $roles, null, ['class' => 'form-control roles-list']) !!}
                    </div>
                </div>
            </div>
            @php do_action(BASE_ACTION_META_BOXES, USER_MODULE_SCREEN_NAME, 'advanced') @endphp
        </div>
        <div class="col-md-3 right-sidebar">
            @include('bases::elements.form-actions')

            @include('bases::elements.forms.status')

            @php do_action(BASE_ACTION_META_BOXES, USER_MODULE_SCREEN_NAME, 'top') @endphp
            @php do_action(BASE_ACTION_META_BOXES, USER_MODULE_SCREEN_NAME, 'side') @endphp
        </div>
    </div>
    {!! Form::close() !!}
@stop
