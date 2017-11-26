<div class="container">
    <div class="row profile">
        <div class="col-md-2">
            <div class="profile-sidebar">
                <div class="profile-userpic">
                    <img src="@if (!empty(acl_get_current_user()->profile_image)) {!! acl_get_current_user()->profile_image !!} @else http://placehold.it/250x250 @endif" class="img-responsive" alt="{{ acl_get_current_user()->getFullName() }}">
                </div>
                <div class="text-center">
                    <div class="profile-usertitle-name">
                        <strong>{{ acl_get_current_user()->getFullName() }}</strong>
                    </div>

                </div>

                <div class="profile-usermenu">
                    <ul class="collection nav nav-stacked">
                        <li>
                            <a href="{{ route('public.account.overview') }}" class="collection-item @if (Route::currentRouteName() == 'public.account.overview') active @endif">{{ __('Overview') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('public.account.edit') }}" class="collection-item @if (Route::currentRouteName() == 'public.account.edit') active @endif">{{ __('Edit Account') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('public.account.change-avatar') }}" class="collection-item @if (Route::currentRouteName() == 'public.account.change-avatar') active @endif">{{ __('Change Avatar') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('public.account.change-password') }}" class="collection-item @if (Route::currentRouteName() == 'public.account.change-password') active @endif">{{ __('Change Password') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('public.access.logout') }}" class="collection-item">{{ __('Logout') }}</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-10">
            @yield('content')
        </div>
    </div>
</div>