@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
        @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), null) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="tabbable-custom tabbable-tabdrop">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_detail" data-toggle="tab">{{ trans('bases::tabs.detail') }}</a>
                        </li>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TABS, null, POST_MODULE_SCREEN_NAME) !!}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_detail">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label for="name" class="control-label required">{{ trans('blog::posts.form.name') }}</label>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::news.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    {!! Form::permalink('slug', old('slug'), null, route('news.create.slug'), route('public.single.detail', config('cms.slug.pattern')), url('/')) !!}
                                    {!! Form::error('slug', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label required">{{ trans('blog::posts.form.description') }}</label>
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::posts.form.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                              <!-- <div class="form-group @if ($errors->has('featured')) has-error @endif">
                                    {!! Form::onOff('featured', old('featured', null)) !!}
                                    <label for="featured">{{ trans('bases::forms.featured') }}</label>
                                    {!! Form::error('featured', $errors) !!}
                                </div>  -->
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::posts.form.content') }}</label>
                                    <a class="btn_gallery" data-mode="attach" data-result="content" data-action="image_post"
                                       data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                       data-target=".media_modal">{{ trans('media::media.add') }}</a>
                                    {!! render_editor('content', old('content'), true) !!}
                                    {!! Form::error('content', $errors) !!}
                                </div>
                            </div>
                        </div>
                       {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, POST_MODULE_SCREEN_NAME, null) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'advanced') @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top') @endphp

                @include('bases::elements.forms.status')

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span class="required">{{ trans('blog::posts.form.format_type') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('format_type')) has-error @endif">
                            <div class="multi-choices-widget list-item-checkbox">
                                {!! Form::customRadio('format_type', get_post_formats(true), old('format_type', ''), 0) !!}
                                {!! Form::error('format_type', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hide">
                    @include('blog::categories.partials.categories-multi', [
                        'name' => 'categories[]',
                        'title' => trans('blog::posts.form.categories'),
                        'value' => old('categories', []),
                        'categories' => $categories,
                        'object' => null
                    ])
                </div>

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span class="required">{{ trans('bases::forms.image') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::mediaImage('image', old('image')) !!}
                        {!! Form::error('image', $errors) !!}
                    </div>
                </div>
                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span>{{ trans('blog::posts.form.tags') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('tag')) has-error @endif">
                            {!! Form::text('tag', old('tag'), ['class' => 'form-control', 'id' => 'tags', 'data-role' => 'tagsinput', 'placeholder' => trans('blog::posts.form.tags_placeholder')]) !!}
                            {!! Form::error('tag', $errors) !!}
                        </div>
                        <div data-tag-route="{{ route('tags.all') }}"></div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
        <script>
            jQuery('.multi-choices-widget .mt-checkbox input').prop('checked', true);  
        </script>
    {!! Form::close() !!}
@stop
