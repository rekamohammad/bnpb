@extends('bases::layouts.master')
@section('content')
    {!! Form::model($post) !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), $post) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="tabbable-custom tabbable-tabdrop">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_detail" data-toggle="tab">
                                {{ trans('bases::tabs.detail') }} </a>
                        </li>
                        <li>
                            <a href="#tab_history" data-toggle="tab">
                                {{ trans('bases::tabs.revision') }} </a>
                        </li>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TABS, null, POST_MODULE_SCREEN_NAME) !!}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_detail">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label for="name" class="control-label required">{{ trans('blog::posts.form.name') }}</label>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::posts.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    {!! Form::permalink('slug', $post->slug, $post->id, route('news.create.slug'), route('public.single.detail', config('cms.slug.pattern')), url('/')) !!}
                                    {!! Form::error('slug', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label required">{{ trans('blog::posts.form.description') }}</label>
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::posts.form.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('featured')) has-error @endif">
                                    {!! Form::onOff('featured', $post->featured) !!}
                                    <label for="featured">{{ trans('bases::forms.featured') }}</label>
                                    {!! Form::error('featured', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::posts.form.content') }}</label>
                                    {!! render_editor('content', $post->content, true) !!}
                                    {!! Form::error('content', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_history">
                            @include('bases::elements.revision', ['model' => $post])
                        </div>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, POST_MODULE_SCREEN_NAME, $post) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'advanced', $post) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top', $post) @endphp

                @include('bases::elements.forms.status', ['selected' => $post->status])

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span class="required">{{ trans('blog::posts.form.format_type') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('format_type')) has-error @endif">
                            <div class="multi-choices-widget list-item-checkbox">
                                {!! Form::customRadio('format_type', get_post_formats(true), $post->format_type, 0) !!}
                                {!! Form::error('format_type', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @include('blog::categories.partials.categories-multi', [
                    'name' => 'categories[]',
                    'title' => trans('blog::posts.form.categories'),
                    'value' => $selected_categories,
                    'categories' => $categories,
                    'object' => $post
                ])

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span class="required">{{ trans('bases::forms.image') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::mediaImage('image', $post->image) !!}
                        {!! Form::error('image', $errors) !!}
                    </div>
                </div>
                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span>{{ trans('blog::posts.form.tags') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('tag')) has-error @endif">
                            {!! Form::text('tag', $tags, ['class' => 'form-control', 'id' => 'tags', 'data-role' => 'tagsinput', 'placeholder' => trans('blog::posts.form.tags_placeholder')]) !!}
                            {!! Form::error('tag', $errors) !!}
                        </div>
                        <div data-tag-route="{{ route('tags.all') }}"></div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side', $post) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop