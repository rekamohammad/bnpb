@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
    @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, CATEGORY_MODULE_SCREEN_NAME, request(), null) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-body">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name_placeholder'), 'data-counter' => 120]) !!}
                            {!! Form::error('name', $errors) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::permalink('slug', old('slug'), null, route('categories.create.slug'), route('public.single.detail', config('cms.slug.pattern')), url('/')) !!}
                            {!! Form::error('slug', $errors) !!}
                        </div>
                        <div class="form-group @if ($errors->has('parent_id')) has-error @endif">
                            <label for="parent_id" class="control-label">{{ trans('bases::forms.parent') }}</label>
                            {!! Form::select('parent_id', $categories, old('parent_id'), ['class' => 'select-search-full', 'id' => 'parent_id']) !!}
                            {!! Form::error('parent_id', $errors) !!}
                        </div>
                        <div class="form-group @if ($errors->has('description')) has-error @endif">
                            <label for="description" class="control-label required">{{ trans('bases::forms.description') }}</label>
                            {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('bases::forms.description'), 'data-counter' => 400]) !!}
                            {!! Form::error('description', $errors) !!}
                        </div>
                        <div class="form-group @if ($errors->has('is_default')) has-error @endif">
                            {!! Form::onOff('is_default', old('is_default', null)) !!}
                            <label for="is_default">{{ trans('bases::forms.is_default') }}</label>
                            {!! Form::error('is_default', $errors) !!}
                        </div>
                        <div class="form-group @if ($errors->has('icon')) has-error @endif">
                            <label for="icon" class="control-label">{{ trans('bases::forms.icon') }}</label>
                            {!! Form::text('icon', old('icon'), ['class' => 'form-control', 'id' => 'icon', 'placeholder' => trans('bases::forms.icon_placeholder'), 'data-counter' => 60]) !!}
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
                @php do_action(BASE_ACTION_META_BOXES, CATEGORY_MODULE_SCREEN_NAME, 'advanced') @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')

                @include('bases::elements.forms.status')

                @php do_action(BASE_ACTION_META_BOXES, CATEGORY_MODULE_SCREEN_NAME, 'top') @endphp
                @php do_action(BASE_ACTION_META_BOXES, CATEGORY_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop
