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
                                    <label for="name" class="control-label required">{{ trans('blog::diorama.form.name') }}</label>
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::diorama.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    {!! Form::permalink('slug', old('slug'), null, route('diorama.create.slug'), route('public.single.detail', config('cms.slug.pattern')), url('/diorama/detail/')) !!}
                                    {!! Form::error('slug', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('options')) has-error @endif">
                                    <label for="options" class="control-label required">{{ trans('blog::diorama.choose_album') }}</label>
                                    <select id="options" name="options[]" class="form-control" required>
                                        @foreach ($albums as $album)
			                                <option value="{{ $album->id }}"> {{ $album->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group @if ($errors->has('image')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::diorama.form.thumbnail') }}</label>
                                    {!! Form::mediaImage('image', old('image')) !!}
                                    {!! Form::error('image', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label for="content" class="control-label required"> Diorama Content</label>
                                    <div class="tabbable-custom tabbable-tabdrop">
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_images" data-toggle="tab"> Image </a>
                                            </li>
                                            <li>
                                                <a href="#tab_video_url" data-toggle="tab"> Video Url</a>
                                            </li>
                                            <li>
                                                <a href="#tab_youtube_url" data-toggle="tab"> Youtube / Vimeo</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_images">
                                                {!! Form::mediaImage('content[]', old('content[]')) !!}
                                                {!! Form::error('content[]', $errors) !!}
                                            </div>
                                            <div class="tab-pane" id="tab_video_url">
                                                <input class="form-control" id="content_video" placeholder="Masukan URL Video " name="content[]" value="" type="text">
                                                {!! Form::error('content[]', $errors) !!}
                                            </div>
                                            <div class="tab-pane" id="tab_youtube_url">
                                                <input class="form-control" id="content_youtube" placeholder="Masukan URL Youtube Video " name="content[]" value="" type="text">
                                                {!! Form::error('content[]', $errors) !!}
                                            </div>
                                            <input class="form-control" id="categories" name="categories[]" value="52" type="hidden">
                                            <input class="form-control" id="diorama_type" name="diorama_type" value="" type="hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label required">{{ trans('blog::diorama.form.description') }}</label>
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::diorama.form.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('featured')) has-error @endif">
                                    {!! Form::onOff('featured', old('featured', null)) !!}
                                    <label for="featured">{{ trans('blog::diorama.form.slide') }}</label>
                                    {!! Form::error('featured', $errors) !!}
                                </div>
                            </div>
                        </div>
                       {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, POST_MODULE_SCREEN_NAME, null) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top') @endphp

                @include('bases::elements.forms.status')

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
        <script>
            jQuery('.multi-choices-widget .mt-checkbox input').prop('checked', true);  
        </script>
    {!! Form::close() !!}
    <script>
        $('#tab_images').on('click', function() {
			$('#diorama_type').val('images');
		});
        $('#tab_video_url').on('click', function() {
			$('#diorama_type').val('video');
		});
        $('#tab_youtube_url').on('click', function() {
			$('#diorama_type').val('youtube');
		});
    </script>
@stop
