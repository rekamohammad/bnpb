@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, TAG_MODULE_SCREEN_NAME, request(), $tag) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name" class="control-label required">{{ trans('blog::tags.form.name') }}</label>
                        {!! Form::text('name', $tag->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::tags.form.name'), 'data-counter' => 120]) !!}
                        {!! Form::error('name', $errors) !!}
                    </div>
                    <div class="form-group @if ($errors->has('slug')) has-error @endif">
                        {!! Form::permalink('slug', $tag->slug, $tag->id, route('tags.create.slug'), route('public.tag', config('cms.slug.pattern')), url('/tag/')) !!}
                        {!! Form::error('slug', $errors) !!}
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label for="description" class="control-label">{{ trans('blog::tags.form.description') }}</label>
                        {!! Form::textarea('description', $tag->description, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('blog::tags.form.description_placeholder'), 'data-counter' => 400]) !!}
                        {!! Form::error('description', $errors) !!}
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'advanced', $tag) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @include('bases::elements.forms.status', ['selected' => $tag->status])
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'top', $tag) @endphp
                @php do_action(BASE_ACTION_META_BOXES, TAG_MODULE_SCREEN_NAME, 'side', $tag) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop
