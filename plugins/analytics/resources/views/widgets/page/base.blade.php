@if (empty($widget_setting) || $widget_setting->status == 1)
    <div class="col-md-6 col-sm-6 col-xs-12 widget_item" id="{{ $widget->name }}" data-url="{{ route('analytics.page') }}">
        <div class="portlet light bordered portlet-no-padding">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark">{{ trans('dashboard::dashboard.' . $widget->name) }}</span>
                </div>
                @include('dashboard::partials.tools', ['settings' => !empty($widget_setting) ? $widget_setting->settings : []])
            </div>
            <div class="portlet-body scroll-table equal-height widget-content"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var widget_page = $('#{{ $widget->name }}').find('.widget-content');
            if (!widget_page.hasClass('widget_hide')) {
                BDashboard.loadWidget(widget_page, '{{ route('analytics.page') }}');
            }
        });
    </script>
@endif