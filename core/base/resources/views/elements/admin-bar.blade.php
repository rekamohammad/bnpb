<nav id="admin_bar">
    <div class="admin-bar-container">
        <div class="admin-bar-logo">
            <a href="{{ route('dashboard.index') }}" title="Go to dashboard">
                <img src="{{ url(config('cms.logo')) }}" alt="logo"/>
            </a>
        </div>
        <ul class="admin-navbar-nav">
            @foreach(admin_bar()->getGroups() as $slug => $group)
                @if(array_get($group, 'items'))
                    <li class="admin-bar-dropdown">
                        <a href="{{ array_get($group, 'link') }}" class="dropdown-toggle">
                            {{ array_get($group, 'title') }}
                        </a>
                        <ul class="admin-bar-dropdown-menu">
                            @foreach(array_get($group, 'items', []) as $title => $link)
                                <li>
                                    <a href="{{ $link or '' }}">
                                        {{ $title or '' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
            @foreach(admin_bar()->getLinksNoGroup() as $item)
                <li>
                    <a href="{{ array_get($item, 'link') }}">{{ array_get($item, 'title') }}</a>
                </li>
            @endforeach
        </ul>
        <ul class="admin-navbar-nav admin-navbar-nav-right">
            <li class="admin-bar-dropdown">
                <a href="{{ route('user.profile.view', ['id' => acl_get_current_user_id()]) }}" class="dropdown-toggle">
                    {{ acl_get_current_user()->getFullName() }}
                </a>
                <ul class="admin-bar-dropdown-menu">
                    <li><a href="{{ route('user.profile.view', acl_get_current_user_id()) }}"><i class="icon-user"></i> {{ trans('bases::layouts.profile') }}</a></li>
                    <li><a href="{{ route('access.logout') }}">{{ trans('bases::layouts.logout') }}</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<script type="text/javascript">
    var the_body = document.getElementsByTagName('body')[0];
    the_body.classList.add('show-admin-bar');
</script>