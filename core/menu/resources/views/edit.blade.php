@extends('bases::layouts.master')

@section('content')
    {!! Form::open(['class' => 'form-save-menu clearfix', 'novalidate' => true]) !!}
    @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, MENU_MODULE_SCREEN_NAME, request(), $menu) @endphp
    <input type="hidden" name="deleted_nodes">
    <textarea name="menu_nodes" id="nestable-output" class="form-control hide"></textarea>
    <div class="row">
        <div class="col-md-9">
            <div class="widget">
                <div class="widget-title">
                    <h4>
                        <i class="box_img_sale"></i><span>{{ trans('menu::menu.basic_info') }}</span>
                    </h4>
                </div>
                <div class="widget-body" style="min-height: 100px;">
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name" class="control-label required">{{ trans('menu::menu.key_name', ['key' => $menu->slug]) }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $menu->name }}" autocomplete="off">
                        {!! Form::error('name', $errors) !!}
                    </div>
                </div>
            </div>
            @if (isset($menu) && $menu->id)
                <div class="row widget-menu">
                    <div class="col-md-4">
                        <div class="panel-group" id="accordion">

                            @php do_action(MENU_ACTION_SIDEBAR_OPTIONS) @endphp

                            <div class="widget panel">
                                <div class="widget-heading">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseCustomLink">
                                        <h4 class="widget-title">
                                            <i class="box_img_sale"></i>
                                            <span>{{ trans('menu::menu.add_link') }}</span>
                                            <i class="fa fa-angle-down narrow-icon"></i>
                                        </h4>
                                    </a>
                                </div>
                                <div id="collapseCustomLink" class="panel-collapse collapse">
                                    <div class="widget-body">
                                        <div class="box-links-for-menu">
                                            <div id="external_link" class="the-box">
                                                <div class="node-content">
                                                    <div class="form-group">
                                                        <label for="node-title">{{ trans('menu::menu.title') }}</label>
                                                        <input type="text" required="required" class="form-control" id="node-title" autocomplete="false">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="node-url">{{ trans('menu::menu.url') }}</label>
                                                        <input type="text" required="required" class="form-control" id="node-url" placeholder="http://" autocomplete="false">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="node-icon">{{ trans('menu::menu.icon') }}</label>
                                                        <input type="text" required="required" class="form-control" id="node-icon" placeholder="fa fa-home" autocomplete="false">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="node-css">{{ trans('menu::menu.css_class') }}</label>
                                                        <input type="text" required="required" class="form-control" id="node-css" autocomplete="false">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="target">{{ trans('menu::menu.target') }}</label>
                                                        <select name="target" class="form-control select-full" id="target">
                                                            <option value="_self">{{ trans('menu::menu.self_open_link') }}</option>
                                                            <option value="_blank">{{ trans('menu::menu.blank_open_link') }}</option>
                                                        </select>
                                                    </div>

                                                    <div class="text-right form-group node-actions hide">
                                                        <a class="btn red btn-remove" href="#">{{ trans('menu::menu.remove') }}</a>
                                                        <a class="btn blue btn-cancel" href="#">{{ trans('menu::menu.cancel') }}</a>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="text-right add-button">
                                                            <div class="btn-group">
                                                                <a href="#" class="btn-add-to-menu btn btn-primary"><span class="text"><i class="fa fa-plus"></i> {{ trans('menu::menu.add_to_menu') }}</span></a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="widget">
                            <div class="widget-title">
                                <h4>
                                    <i class="box_img_sale"></i><span>{{ trans('menu::menu.structure') }}</span>
                                </h4>
                            </div>
                            <div class="widget-body">
                                <div class="dd nestable-menu" id="nestable" data-depth="0">
                                    {!!
                                         Menu::generateMenu([
                                            'slug' => $menu->slug,
                                            'view' => 'menu::partials.menu',
                                            'theme' => false,
                                            'active' => false
                                         ])
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php do_action(BASE_ACTION_META_BOXES, MENU_MODULE_SCREEN_NAME, 'advanced', $menu) @endphp
        </div>
        <div class="col-md-3 right-sidebar">
            @include('bases::elements.form-actions')
            @php do_action(BASE_ACTION_META_BOXES, MENU_MODULE_SCREEN_NAME, 'top', $menu) @endphp
        </div>
    </div>
    {!! Form::close() !!}
@endsection
