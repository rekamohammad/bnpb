<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">

        <button type="button" class="navbar-toggle offcanvas">
            <span class="sr-only">Toggle navigation</span>
            <i class="icon icon-menu"></i>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">
            <img src="{{ url(theme_option('logo')) }}" alt="{{ setting('site_title') }}">
        </a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
            <span class="sr-only">Toggle navbar</span>
            <i class="icon icon-grid"></i>
        </button>
        <div class="menu-toggler sidebar-toggle">
            <span></span>
        </div>
    </div>

    <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">

        <li class="dropdown">
            <a class="dropdown-toggle dropdown-header-name" style="padding-right: 10px" href="{{ route('public.index') }}" target="_blank"><i class="fa fa-globe"></i> <span>{{ __('View website') }}</span> </a>
        </li>

        @if (acl_check_login())
            {!! apply_filters(BASE_FILTER_TOP_HEADER_LAYOUT, null) !!}
        @endif

        @if (isset($themes) && setting('enable_change_admin_theme') != false)
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <span>{{ trans('bases::layouts.theme') }}</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right icons-right">

                    @foreach ($themes as $name => $file)
                        @if ($active_theme === $name)
                            <li class="active"><a href="{{ route('admin.theme', [$name]) }}">{{ studly_case($name) }}</a></li>
                        @else
                            <li><a href="{{ route('admin.theme', [$name]) }}">{{ studly_case($name) }}</a></li>
                        @endif
                    @endforeach

                </ul>
            </li>
        @endif

        @if (setting('enable_multi_language_in_admin') != false)
            <li class="language dropdown">
                <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    @if (array_key_exists(app()->getLocale(), $locales))
                        {!! language_flag($locales[app()->getLocale()]['flag'], $locales[app()->getLocale()]['name']) !!}
                        <span class="hidden-xs">{{ $locales[app()->getLocale()]['name'] }}</span>
                    @endif
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right icons-right">
                    @foreach ($locales as $key => $value)
                        @if (app()->getLocale() == $key)
                            <li class="active">
                                <a href="{{ route('admin.language', $key) }}">
                                    {!! language_flag($value['flag'], $value['name']) !!} <span>{{ $value['name'] }}</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('admin.language', $key) }}">
                                    {!! language_flag($value['flag'], $value['name']) !!} <span>{{ $value['name'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif

        @if (acl_check_login())
            <li class="dropdown dropdown-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <img alt="{{ acl_get_current_user()->getFullName() }}" class="img-circle" src="{{ url(acl_get_current_user()->getProfileImage()) }}" />
                    <span class="username username-hide-on-mobile"> {{ acl_get_current_user()->getFullName() }} </span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                    <li><a href="{{ route('user.profile.view', acl_get_current_user_id()) }}"><i class="icon-user"></i> {{ trans('bases::layouts.profile') }}</a></li>
                    <li><a href="{{ route('access.logout') }}"><i class="icon-key"></i> {{ trans('bases::layouts.logout') }}</a></li>
                </ul>
            </li>
        @endif
    </ul>
</div>
<!-- /navbar -->