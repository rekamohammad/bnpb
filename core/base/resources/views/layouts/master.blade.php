@extends('bases::layouts.base')

@section ('page')

    @include('bases::layouts.partials.top-header')

    <!-- Page container -->
    <div class="page-container col-md-12 @yield('page-class')">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-content">
                <!-- User dropdown -->
                <!-- <div class="user-menu dropdown">
                    <a href="{{ route('user.profile.view', ['id' => acl_get_current_user_id()]) }}"
                       class="dropdown-toggle">
                        <img alt="profile image" src="{{ url(acl_get_current_user()->getProfileImage()) }}"
                             class="img-circle">
                        <div class="user-info">
                            {{ acl_get_current_user()->getFullName() }}
                            <span>{{ acl_get_current_user()->job_position }}</span>
                        </div>
                    </a>

                </div> -->
                <!-- /user dropdown -->
                <!-- Main navigation -->
                <ul class="navigation">
                    @include('bases::layouts.partials.sidebar')
                </ul>
                <!-- /main navigation -->
            </div>
        </div>
        <!-- /sidebar -->

        <!-- Page content -->
        <div class="page-content">
            {!! AdminBreadcrumb::render() !!}
            <div class="clearfix"></div>
            @yield('content')

            @include('bases::layouts.partials.footer')
        </div>
    <!-- /page content -->
        <div class="clearfix"></div>
    </div>
    <!-- /page container -->
@stop

@section('javascript')
    @include('media::partials.media')
@endsection

