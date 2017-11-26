@extends('acl::auth.my-account.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ __('Change profile image') }}
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => 'public.account.change-avatar', 'files' => true]) !!}
                <div class="form-group @if ($errors->has('profile_image')) has-error @endif">
                    <label for="profile_image">{{ __('Profile image') }}</label>
                    <input type="file" id="profile_image" name="profile_image">
                    {!! Form::error('profile_image', $errors) !!}
                </div>
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Change') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection