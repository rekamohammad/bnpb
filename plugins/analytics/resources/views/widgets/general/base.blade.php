@if (empty($widget_setting) || $widget_setting->status == 1)
    <div class="col-md-12 col-sm-12 col-xs-12 widget_item" id="{{ $widget->name }}" data-url="{{ route('analytics.general') }}">
        <div class="portlet light bordered portlet-no-padding">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark">{{ trans('dashboard::dashboard.' . $widget->name) }}</span>
                </div>
                @include('dashboard::partials.tools', ['settings' => !empty($widget_setting) ? $widget_setting->settings : []])
            </div>
            <div class="portlet-body widget-content {{ array_get(!empty($widget_setting) ? $widget_setting->settings : [], 'state') }}"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            BDashboard.loadWidget($('#{{ $widget->name }}').find('.widget-content'), '{{ route('analytics.general') }}');
        });
    </script>
@endif