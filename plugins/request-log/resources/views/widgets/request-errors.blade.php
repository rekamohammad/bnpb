@if ($requests->count() > 0)
<div class="scroller">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('bases::tables.url') }}</th>
                <th>{{ trans('request-logs::request-log.status_code') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($requests as $request)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td><a href="{{ $request->url }}" target="_blank">{{ string_limit_words($request->url, 55) }}</a></td>
                <td>{{ $request->status_code }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="widget_footer">
    @include('dashboard::partials.paginate', ['data' => $requests, 'limit' => $limit])
</div>
@else
    <div class="dashboard_widget_msg">
        <p class="smiley" aria-hidden="true"></p>
        <p>{{ trans('request-logs::request-log.no_request_error') }}</p>
    </div>
@endif