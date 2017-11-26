@extends('bases::layouts.master')
@section('content')
    <div class="widget-main" id="wrap-widgets">
        <div class="row">
            <div class="col-sm-6">
                <h2>{{ trans('widgets::global.available') }}</h2>
                <p>{{ trans('widgets::global.instruction') }}</p>
                <ul id="wrap-widget-1">
                    @foreach (Widget::getWidgets() as $widget)
                        <li data-id="{{ $widget->getId() }}">
                            <div class="widget-handle">
                                <p class="widget-name">{{ $widget->getConfig()['name'] }} <span class="text-right"><i class="fa fa-caret-up"></i></span>
                                </p>
                            </div>
                            <div class="widget-content">
                                <form method="post">
                                    <input type="hidden" name="id" value="{{ $widget->getId() }}">
                                    {!! $widget->form() !!}
                                    <div class="widget-control-actions">
                                        <div class="pull-left">
                                            <button class="btn btn-danger widget-control-delete">{{ trans('widgets::global.delete') }}</button>
                                        </div>
                                        <div class="pull-right text-right">
                                            <button class="btn btn-primary widget_save">{{ trans('bases::forms.save') }}</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-description">
                                <p>{{ $widget->getConfig()['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-6" id="added-widget">
                <div class="row">
                    @php $index = 1; @endphp
                    @foreach (WidgetGroup::getGroups() as $group)
                        <div class="col-sm-6 sidebar-item" data-id="{{ $group->getId() }}">
                            <div class="sidebar-area">
                                <div class="sidebar-header">
                                    <h3>{{ $group->getName() }}</h3>
                                    <p>{{ $group->getDescription() }}</p>
                                </div>
                                @php $index++; $widget_areas = $group->getWidgets() @endphp
                                <ul id="wrap-widget-{{ $index }}">
                                    @include('widgets::item', compact('widget_areas', 'position'))
                                    <div class="clearfix"></div>
                                </ul>
                            </div>
                        </div>
                        @if ($loop->index % 2 != 0)
                            <div class="clearfix"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        var BWidget = BWidget || {};
        BWidget.routes = {
            'delete': '{{ route('widgets.delete') }}',
            'save_widgets_sidebar': '{{ route('widgets.save_widgets_sidebar') }}'
        };
    </script>
@stop