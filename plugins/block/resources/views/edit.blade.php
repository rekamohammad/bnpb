@extends('bases::layouts.master')
@section('content')
    {!! Form::open(['route' => ['block.edit', $block->id]]) !!}
    @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, MENU_MODULE_SCREEN_NAME, request(), $block) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="main-form">
                    <div class="form-body">
                        <div class="form-body">
                            <div class="form-group @if ($errors->has('name')) has-error @endif">
                                <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                                {!! Form::text('name', $block->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name_placeholder'), 'data-counter' => 120]) !!}
                                {!! Form::error('name', $errors) !!}
                            </div>
                            <div class="form-group @if ($errors->has('alias')) has-error @endif">
                                <label for="alias" class="control-label required">{{ trans('bases::forms.alias') }}</label>
                                {!! Form::text('alias', $block->alias, ['class' => 'form-control', 'id' => 'alias', 'placeholder' => trans('bases::forms.alias_placeholder'), 'data-counter' => 120]) !!}
                                {!! Form::error('alias', $errors) !!}
                            </div>
                            <div class="form-group @if ($errors->has('description')) has-error @endif">
                                <label for="description" class="control-label">{{ trans('bases::forms.description') }}</label>
                                {!! Form::textarea('description', $block->description, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('bases::forms.description_placeholder'), 'data-counter' => 400]) !!}
                                {!! Form::error('description', $errors) !!}
                            </div>
                            <div class="form-group @if ($errors->has('content')) has-error @endif">
                                <label class="control-label">{{ trans('bases::forms.content') }}</label>
                                {!! render_editor('content', $block->content) !!}
                                {!! Form::error('content', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @php do_action(BASE_ACTION_META_BOXES, BLOCK_MODULE_SCREEN_NAME, 'advanced', $block) @endphp
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @include('bases::elements.forms.status', ['selected' => $block->status])
                @php do_action(BASE_ACTION_META_BOXES, BLOCK_MODULE_SCREEN_NAME, 'top', $block) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop
