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
                                    <label for="name" class="control-label required">{{ trans('blog::infografis.form.name') }}</label>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::infografis.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('categories')) has-error @endif">
                                    <label for="categories" class="control-label required">{{ trans('blog::infografis.form.categories') }}</label>
                                    <select id="categories" name="categories[]" class="form-control" required>
                                        @foreach ($categories as $category)
			                                <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="section_options" class="form-group @if ($errors->has('options')) has-error @endif">
                                    <label for="options" class="control-label required">{{ trans('blog::infografis.form.options') }}</label>
                                    <select id="options" name="options[]" class="form-control" required>
                                        <option value="1"> Set To Homepage </option>
			                            <option value="0"> Don't Set To Homepage </option>
                                    </select>
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    {!! Form::permalink('slug', old('slug'), null, route('infografis.create.slug'), route('public.single.detail', config('cms.slug.pattern')), url('/infografis/detail/')) !!}
                                    {!! Form::error('slug', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('image')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::infografis.form.thumbnail') }}</label>
                                    {!! Form::mediaImage('image', old('image')) !!}
                                    {!! Form::error('image', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::infografis.form.content') }}</label>
                                    {!! render_editor('content', old('content'), true) !!}
                                    {!! Form::error('content', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label required">{{ trans('blog::infografis.form.description') }}</label>
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::infografis.form.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top') @endphp

                @include('bases::elements.forms.status')

                <div class="widget meta-boxes">
                    <div class="widget-title">
                        <h4><span>{{ trans('blog::infografis.form.tags') }}</span></h4>
                    </div>
                    <div class="widget-body">
                        <div class="form-group @if ($errors->has('tag')) has-error @endif">
                            {!! Form::text('tag', old('tag'), ['class' => 'form-control', 'id' => 'tags', 'data-role' => 'tagsinput', 'placeholder' => trans('blog::infografis.form.tags_placeholder')]) !!}
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
            $('#section_options').hide();
            $('#categories').on('change', function() {
                var categories_val = $('#categories').val();
                if (categories_val == 72 || categories_val == 70) {
                    $('#section_options').show();
                } else {
                    $('#section_options').hide();
                }
            });
        </script>
    {!! Form::close() !!}
@stop
