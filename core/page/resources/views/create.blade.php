@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
        @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, PAGE_MODULE_SCREEN_NAME, request(), null) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="tabbable-custom tabbable-tabdrop">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_detail" data-toggle="tab">{{ trans('bases::tabs.detail') }} </a>
                        </li>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TABS, null, PAGE_MODULE_SCREEN_NAME) !!}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_detail">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label for="name" class="control-label required">{{ trans('pages::pages.form.name') }}</label>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('pages::pages.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    {!! Form::permalink('slug', old('slug'), null, route('pages.create.slug')) !!}
                                    {!! Form::error('slug', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label">{{ trans('bases::forms.description') }}</label>
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('bases::forms.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label class="control-label required">{{ trans('pages::pages.form.content') }}</label>
                                    {!! render_editor('content', old('content'), true) !!}
                                    {!! Form::error('content', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('icon')) has-error @endif">
                                    <label for="icon" class="control-label">{{ trans('bases::forms.icon') }}</label>
                                    {!! Form::text('icon', old('icon'), ['class' => 'form-control', 'id' => 'icon', 'placeholder' => 'Ex: fa fa-home', 'data-counter' => 60]) !!}
                                    {!! Form::error('icon', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('order')) has-error @endif">
                                    <label for="order" class="control-label">{{ trans('bases::forms.order_by') }}</label>
                                    {!! Form::text('order', old('order', 0), ['class' => 'form-control', 'id' => 'order', 'placeholder' => trans('bases::forms.order_by_placeholder'), 'data-counter' => 60]) !!}
                                    {!! Form::error('order', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('featured')) has-error @endif">
                                    {!! Form::onOff('featured', old('featured', null)) !!}
                                    <label for="featured">{{ trans('bases::forms.featured') }}</label>
                                    {!! Form::error('featured', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, PAGE_MODULE_SCREEN_NAME, null) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, PAGE_MODULE_SCREEN_NAME, 'advanced') @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @include('bases::elements.forms.status')
                @php do_action(BASE_ACTION_META_BOXES, PAGE_MODULE_SCREEN_NAME, 'top') @endphp
                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span>{{ trans('bases::forms.template') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('template')) has-error @endif">
                            {!! Form::select('template', $templates, old('template'), ['class' => 'form-control select-full', 'id' => 'template']) !!}
                            {!! Form::error('template', $errors) !!}
                        </div>
                    </div>
                </div>

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span>{{ trans('bases::forms.image') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::mediaImage('image', old('image')) !!}
                        {!! Form::error('image', $errors) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, PAGE_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop
