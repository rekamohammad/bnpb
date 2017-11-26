@if (count($referrers) > 0)
    <div class="scroller">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('bases::tables.url') }}</th>
                    <th>{{ trans('bases::tables.views') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($referrers as $referrer)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-left">{{ $referrer['url'] }}</td>
                        <td>{{ $referrer['pageViews'] }} ({{ ucfirst(trans('analytics::analytics.views')) }})</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>{{ trans('bases::tables.no_data') }}</p>
@endif