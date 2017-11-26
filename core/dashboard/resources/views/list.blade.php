@extends('bases::layouts.master')
@section('content')
    {!! apply_filters(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, null) !!}
    <div class="row">
        {!! apply_filters(DASHBOARD_FILTER_TOP_BLOCKS, null) !!}
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div id="list_widgets">
            @foreach ($user_widgets as $widget)
                {!! $widget !!}
            @endforeach
            <div class="clearfix"></div>
        </div>
    </div>

    <a href="#" class="manage-widget"><i class="fa fa-plus"></i> {{ trans('dashboard::dashboard.manage_widgets') }}</a>

    @include('dashboard::partials.modals', compact('widgets'))

@stop

@section('javascript')
    <script>
        var BDashboard = BDashboard || {};
        BDashboard.routes = {
            edit_widget_item: '{{ route('dashboard.edit_widget_setting_item') }}',
            update_widget_order: '{{ route('dashboard.update_widget_order') }}',
            hide_widget: '{{ route('dashboard.hide_widget') }}'
        };
    </script>
@stop