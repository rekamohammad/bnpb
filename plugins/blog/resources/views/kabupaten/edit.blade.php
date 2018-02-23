@extends('bases::layouts.master')
@section('content')
    {!! Form::model($kabupaten) !!}
        @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), $kabupaten) @endphp
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
                                    <label for="provinsi" class="control-label required">{{ trans('blog::kabupaten.form.provinsi') }}</label>
                                    <select class="form-control" name="provinsi" id="provinsi">
											<option value="">Choose Province</option>
										@foreach ($provinsi_post as $pp)
										   @if($pp->id == $kabupaten->province)
											<option value="{{ $pp->id }}" selected>{{ $pp->name }}</option>
										   @else
											<option value="{{ $pp->id }}">{{ $pp->name }}</option>	
										   @endif
										@endforeach
									</select>
                                    {!! Form::error('kabupaten', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('kabupaten')) has-error @endif">
                                    <label for="kabupaten" class="control-label required">{{ trans('blog::kabupaten.form.kabupaten') }}</label>
                                    {!! Form::text('kabupaten', null, ['class' => 'form-control', 'id' => 'kabupaten', 'placeholder' => trans('blog::kabupaten.form.name_placeholder'), 'data-counter' => 120]) !!}
                                    {!! Form::error('kabupaten', $errors) !!}
                                </div>
                                <div class="form-group @if ($errors->has('address')) has-error @endif">
                                    <label class="control-label required">{{ trans('blog::kabupaten.form.address') }}</label>
                                    {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 4, 'id' => 'address', 'placeholder' => trans('blog::kabupaten.form.address_placeholder')]) !!}
                                    {!! Form::error('address', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_history">
                            @include('bases::elements.revision', ['model' => $kabupaten])
                        </div>
                        {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, POST_MODULE_SCREEN_NAME, $kabupaten) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')
                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'top', $kabupaten) @endphp

                @include('bases::elements.forms.status', ['selected' => $kabupaten->status])

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side', $kabupaten) @endphp
            </div>
        </div>
    {!! Form::close() !!}
@stop