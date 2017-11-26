@extends('acl::auth.my-account.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ __('Edit profile') }}
        </div>
        <div class="panel-body">

            {!! Form::open(['route' => 'public.account.edit']) !!}
                <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                    <label for="first_name">{{ __('First Name') }}</label>
                    <input id="first_name" class="form-control" type="text" value="{{ acl_get_current_user()->first_name }}" name="first_name">
                    {!! Form::error('first_name', $errors) !!}
                </div>

                <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                    <label for="last_name">{{ __('Last Name') }}</label>
                    <input id="last_name" type="text" class="form-control" value="{{ acl_get_current_user()->last_name }}" name="last_name">
                    {!! Form::error('last_name', $errors) !!}
                </div>

                <div class="form-group @if ($errors->has('dob')) has-error @endif">
                    <label for="date_of_birth">{{ __('Date of birth') }}</label>
                    <input id="date_of_birth" type="text" class="form-control" name="dob" value="{{ acl_get_current_user()->dob }}">
                    {!! Form::error('dob', $errors) !!}
                </div>

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="text" class="form-control" disabled="disabled" value="{{ acl_get_current_user()->email }}" name="email">
                    {!! Form::error('email', $errors) !!}
                </div>

                <div class="form-group @if ($errors->has('phone')) has-error @endif">
                    <label for="phone">{{ __('Phone') }}</label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="{{ __('Phone') }}" value="{{ acl_get_current_user()->phone }}">
                    {!! Form::error('phone', $errors) !!}
                </div>

                <div class="form-group @if ($errors->has('company')) has-error @endif">
                    <label for="company_name">{{ __('Company Name') }}</label>
                    <input type="text" class="form-control" name="company" id="company_name" placeholder="{{ __('Company Name') }}" value="{{ get_user_meta('company') }}">
                    {!! Form::error('company', $errors) !!}
                </div>

                <div class="form-group col s12">
                    <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection