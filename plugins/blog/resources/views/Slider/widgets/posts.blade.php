@if ($posts->count() > 0)
<div class="scroller">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('bases::tables.name') }}</th>
            <th>{{ trans('bases::tables.created_at') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td><a href="{{ route('public.single.detail', $post->slug) }}" target="_blank">{{ string_limit_words($post->name, 55) }}</a></td>
                <td>{{ date_from_database($post->created_at, 'd-m-Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="widget_footer">
    @include('dashboard::partials.paginate', ['data' => $posts, 'limit' => $limit])
</div>
@else
    <div class="dashboard_widget_msg">
        <p>{{ trans('blog::posts.no_new_post_now') }}</p>
    </div>
@endif