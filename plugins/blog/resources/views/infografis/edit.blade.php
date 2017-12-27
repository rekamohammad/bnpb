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
                                    <label for="name" class="control-label required">{{ trans('blog::infografis.form.name') }}</label>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::infografis.form.name_placeholder'), 'data-counter' => 120]) !!}
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
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    <div id="edit-slug-box">
                                        <label class="control-label required" for="current-slug">Permalink:</label>
                                        <span id="sample-permalink">
                                            <a class="permalink" target="_blank" href="{{ url('/infografis/detail/'.substr($post->slug,18)) }}">
                                                <span class="default-slug">{{ url('infografis/detail/') }}/<span id="editable-post-name">{{ substr($post->slug,18) }}</span>.html</span>
                                            </a>
                                        </span>
                                        ‎<span id="edit-slug-buttons">
                                            <button type="button" class="btn btn-default" id="change_slug">Edit</button>
                                            <button type="button" class="save btn btn-default" id="btn-ok">OK</button>
                                            <button type="button" class="cancel button-link">Cancel</button>
                                        </span>
                                    </div>
                                    <input id="current-slug" name="slug" value="{{ substr($post->slug,18) }}" type="hidden">
                                    <div data-url="{{ url('/admin/infografis/create-slug/') }}" data-view="{{ url('/infografis/detail/'.substr($post->slug,18)) }}" id="object_id" data-id="{{ $post->id }}"></div>
                                </div>
                                <div class="form-group @if ($errors->has('image')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::infografis.form.thumbnail') }}</label>
                                    {!! Form::mediaImage('image', $post->image) !!}
                                    {!! Form::error('image', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('content')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::infografis.form.content') }}</label>
                                    {!! render_editor('content', old('content'), true) !!}
                                    {!! Form::error('content', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('description')) has-error @endif">
                                    <label for="description" class="control-label required">{{ trans('blog::infografis.form.description') }}</label>
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::infografis.form.description_placeholder'), 'data-counter' => 300]) !!}
                                    {!! Form::error('description', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_history">
                            @include('bases::elements.revision', ['model' => $post])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top', $post) @endphp

                @include('bases::elements.forms.status', ['selected' => $post->status])

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side', $post) @endphp
            </div>
        </div>
    <script>
        $('#categories').val('{{ $currentCategory[0]->id }}')
    </script>
    {!! Form::close() !!}
@stop