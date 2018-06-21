@extends('bases::layouts.master')
@section('content')
    {!! Form::model($mountain) !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), $mountain) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="tabbable-custom tabbable-tabdrop">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_detail" data-toggle="tab">
                                {{ trans('bases::tabs.detail') }} </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_detail">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label for="name" class="control-label required">{{ trans('blog::posts.form.name') }}</label>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('blog::posts.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('name', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('mountain_status')) has-error @endif">
                                    <label for="mountain_status" class="control-label required">{{ trans('blog::mountains.form.mountain_status') }}</label>
                                    <select id="mountain_status" name="mountain_status" class="form-control" required>
                                        <option value="awas"> Awas </option>
                                        <option value="siaga"> Siaga </option>
                                        <option value="waspada"> Waspada </option>
                                    </select>
                                </div>
                                <div class="form-group @if ($errors->has('date_of_the_incident')) has-error @endif">
                                    <label for="date_of_the_incident" class="control-label required">{{ trans('blog::mountains.form.date_of_the_incident') }}</label>
                                    {!! Form::date('date_of_the_incident', $mountain->date_of_the_incident, ['class' => 'form-control', 'id' => 'date_of_the_incident', 'placeholder' => trans('blog::mountains.form.name_placeholder')]) !!}
                                    {!! Form::error('date_of_the_incident', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('notes')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::posts.form.note') }}</label>
                                    {!! render_editor('notes', $mountain->notes, true) !!}
                                    {!! Form::error('notes', $errors) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top', $mountain) @endphp

                @include('bases::elements.forms.status', ['selected' => $mountain->status])

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side', $mountain) @endphp
            </div>
        </div>
    <script>
        $('#mountain_status').val('{{ $mountain->mountain_status }}');
    </script>
    {!! Form::close() !!}
@stop