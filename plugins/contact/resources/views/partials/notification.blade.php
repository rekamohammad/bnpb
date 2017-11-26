<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <i class="icon-envelope-open"></i>
        <span class="badge badge-default"> {{ count($contacts) }} </span>
    </a>
    <ul class="dropdown-menu">
        <li class="external">
            <h3>{!! trans('contact::contact.new_msg_notice', ['count' => count($contacts)]) !!}</h3>
            <a href="{{ route('contacts.list') }}">{{ trans('contact::contact.view_all') }}</a>
        </li>
        <li>
            <ul class="dropdown-menu-list scroller" style="height: {{ count($contacts) * 70 }}px;" data-handle-color="#637283">
                @foreach($contacts as $contact)
                    <li>
                        <a href="{{ route('contacts.edit', $contact->id) }}">
                            <span class="photo">
                                <img src="{{ url(config('acl.avatar.default')) }}" class="img-circle" alt="{{ $contact->name }}">
                            </span>
                            <span class="subject"><span class="from"> {{ $contact->name }} </span><span class="time">{{ Carbon::parse($contact->created_at)->toDateTimeString() }} </span></span>
                            <span class="message"> {{ $contact->phone }} - {{ $contact->email }} </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>