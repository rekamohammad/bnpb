<div class="scroller">
    <ul class="item-list padding">
        @foreach ($histories as $history)
            <li>
                <span class="log-icon log-icon-{{ $history->type }}"></span>
                <span>
                @if (!empty($history->user))
                    <a href="{{ route('user.profile.view', $history->user->id) }}">{{ $history->user->getFullName() }}</a>
                @endif
                @if (Lang::has('audit-logs.history.' . $history->action)) {{ trans('audit-logs.history.' . $history->action) }} @else {{ $history->action }} @endif
                @if ($history->module)
                    @if (Lang::has('audit-logs.history.' . $history->module)) {{ trans('audit-logs.history.' . $history->module) }} @else {{ $history->module }} @endif
                @endif
                @if ($history->reference_name)
                    @if (empty($history->user) || $history->user->getFullName() != $history->reference_name)
                        "{{ string_limit_words($history->reference_name, 30) }}"
                    @endif
                @endif
                    .
            </span>
                <span class="small italic">{{ Carbon::parse($history->created_at)->diffForHumans() }} </span>
                <span>({{ $history->ip_address }})</span>
            </li>
        @endforeach
    </ul>
</div>
<div class="widget_footer">
    @include('dashboard::partials.paginate', ['data' => $histories, 'limit' => $limit])
</div>
