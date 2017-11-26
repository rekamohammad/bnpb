@extends('acl::auth.my-account.master')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            {{ __('Profile Details') }}
        </div>
        <div class="panel-body">
            <p><i class="fa fa-user"></i> {{ __('Name') }}: {{ acl_get_current_user()->getFullName() }}</p>
            <p><i class="fa fa-calendar"></i> {{ __('Date of birth') }}: {{ acl_get_current_user()->dob }}</p>
            <p><i class="fa fa-envelope-o"></i> {{ __('Email') }}: {{ acl_get_current_user()->email }}</p>
            <p><i class="fa fa-phone"></i> {{ __('Phone') }}: {{ acl_get_current_user()->phone }}</p>
            <p><i class="fa fa-home"></i> {{ __('Company') }}: {{ get_user_meta('company') }}</p>
        </div>
    </div>
@endsection