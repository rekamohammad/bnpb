@extends('bases::layouts.master')
@section('content')
    {!! Form::model($provinsi) !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), $provinsi) @endphp
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
                                <div class="form-group @if ($errors->has('provinsi')) has-error @endif">
                                    <label for="provinsi" class="control-label required">{{ trans('blog::provinsi.form.provinsi') }}</label>
                                    {!! Form::text('provinsi', $provinsi->prov->name, ['class' => 'form-control', 'id' => 'provinsi', 'placeholder' => trans('blog::provinsi.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('provinsi', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('address')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::provinsi.form.address') }}</label>
                                    {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 4, 'id' => 'address', 'placeholder' => trans('blog::provinsi.form.address_placeholder')]) !!}
                                    {!! Form::error('address', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_history">
                            @include('bases::elements.revision', ['model' => $provinsi])
                        </div>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, POST_MODULE_SCREEN_NAME, $provinsi) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top', $provinsi) @endphp

                @include('bases::elements.forms.status', ['selected' => $provinsi->status])

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side', $provinsi) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop