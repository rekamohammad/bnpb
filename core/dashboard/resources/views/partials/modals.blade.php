<div id="hide_widget_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('dashboard::dashboard.confirm_hide') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{{ trans('dashboard::dashboard.hide_message') }}</p>
            </div>

            <div class="modal-footer">
                <a class="pull-left btn btn-danger" id="hide-widget-confirm-bttn">{{ trans('dashboard::dashboard.confirm_hide_btn') }}</a>
                <button class="pull-right btn btn-primary" data-dismiss="modal">{{ trans('dashboard::dashboard.cancel_hide_btn') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="manage_widget_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'dashboard.hide_widgets']) !!}
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('dashboard::dashboard.manage_widgets') }}</strong></h4>
                </div>
                <div class="modal-body with-padding">
                    @foreach ($widgets as $widget)
                        @php $widget_setting = $widget->userSetting()->first(); @endphp
                        <section class="wrap_{{ $widget->name }}">
                            <i class="box_img_sale {{ $widget->name }} @if ($widget_setting && $widget_setting->status == 0) widget_none_color @endif"></i>
                            <span class="widget_name">{{ trans('dashboard::dashboard.' . $widget->name) }}</span>
                            <div class="swc_wrap">
                                {!! Form::onOff('widgets[' . $widget->name . ']', $widget_setting ? $widget_setting->status : true, ['data-target' => $widget->name]) !!}
                            </div>
                        </section>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">{{ trans('bases::forms.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('bases::forms.save') }}</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
