@extends('bases::layouts.master')

@section('content')
    {!! Form::open(['class' => 'js-validate-form form-update-field-group', 'url' => route('custom-fields.edit', ['id' => $object->id])]) !!}
    <textarea name="rules"
              id="custom_fields_rules"
              class="form-control hidden"
              style="display: none !important;">{!! ((isset($object->rules) && $object->rules) ? $object->rules : '[]') !!}</textarea>
    <textarea name="group_items"
              id="custom_fields"
              class="form-control hidden"
              style="display: none !important;">{!! $customFieldItems or '[]' !!}</textarea>
    <textarea name="deleted_items"
              id="deleted_items"
              class="form-control hidden"
              style="display: none !important;"></textarea>
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-snowflake-o font-dark"></i>
                        {{ trans('custom-field::custom-field.basic_information') }}
                    </h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        <label class="control-label required">
                            <b>{{ trans('custom-field::custom-field.title') }}</b>
                        </label>
                        <input required type="text"
                               name="title"
                               class="form-control"
                               value="{{ $object->title or '' }}"
                               autocomplete="off">
                        {!! Form::error('title', $errors) !!}
                    </div>
                    <div class="form-group @if ($errors->has('status')) has-error @endif">
                        <label class="control-label required">
                            <b>{{ trans('custom-field::custom-field.status') }}</b>
                        </label>
                        <select name="status" class="form-control select-full">
                            <option
                                value="1" {{ (isset($object) && $object->status == '1') ? 'selected' : '' }}>
                                {{ trans('custom-field::custom-field.activated') }}
                            </option>
                            <option
                                value="0" {{ (isset($object) && $object->status == '0') ? 'selected' : '' }}>
                                {{ trans('custom-field::custom-field.deactivated') }}
                            </option>
                        </select>
                        {!! Form::error('status', $errors) !!}
                    </div>
                    <div class="form-group @if ($errors->has('order')) has-error @endif">
                        <label class="control-label">
                            <b>{{ trans('custom-field::custom-field.sort_order') }}</b>
                        </label>
                        <input required type="text"
                               name="order"
                               class="form-control"
                               value="{{ $object->order or 0 }}"
                               autocomplete="off">
                        {!! Form::error('order', $errors) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-snowflake-o font-dark"></i>
                        {{ trans('custom-field::custom-field.rules') }}
                    </h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="custom-fields-rules">
                        <label class="control-label required">
                            <b>{{ trans('custom-field::custom-field.rules') }}</b>
                        </label>
                        <span class="help-block">{{ trans('custom-field::custom-field.rules_description') }}</span>
                        <div class="line-group-container"></div>
                        <div class="line">
                            <p class="mt20"><b>{{ trans('custom-field::custom-field.or') }}</b></p>
                            <a class="location-add-rule-or location-add-rule btn btn-info" href="#">
                                {{ trans('custom-field::custom-field.add_rule_group') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-snowflake-o font-dark"></i>
                {{ trans('custom-field::custom-field.field_group_information') }}
            </h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <div class="custom-fields-list">
                    <div class="nestable-group">
                        <div class="add-new-field">
                            <ul class="list-group field-table-header clearfix">
                                <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_label') }}</li>
                                <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_name') }}</li>
                                <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_type') }}</li>
                            </ul>
                            <div class="clearfix"></div>
                            <ul class="sortable-wrapper edit-field-group-items field-group-items"
                                id="custom_field_group_items"></ul>
                            <div class="text-right">
                                <a class="btn btn-info btn-add-field"
                                   href="#">{{ trans('custom-field::custom-field.add_field') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-primary" type="submit" value="save">
                    <i class="fa fa-check"></i> {{ trans('custom-field::custom-field.save') }}
                </button>
                <button class="btn btn-success" type="submit" value="apply">
                    <i class="fa fa-check"></i> {{ trans('custom-field::custom-field.save_and_continue') }}
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    @include('custom-field::_script-templates.edit-field-group-items')
@endsection
