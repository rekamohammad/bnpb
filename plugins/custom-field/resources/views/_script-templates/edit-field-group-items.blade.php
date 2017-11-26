{!! CustomField::render() !!}
<script id="_options-repeater_template" type="text/x-custom-template">
    <div class="line" data-option="repeater">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.repeater_fields') }}</h5>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.repeater_fields') }}</h5>
            <div class="add-new-field">
                <ul class="list-group field-table-header clearfix">
                    <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_label') }}</li>
                    <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_name') }}</li>
                    <li class="col-xs-4 list-group-item w-bold">{{ trans('custom-field::custom-field.field_type') }}</li>
                </ul>
                <div class="clearfix"></div>
                <ul class="sortable-wrapper edit-field-group-items field-group-items">

                </ul>
                <div class="text-right pt10">
                    <a class="btn btn-info btn-add-field" href="#">{{ trans('custom-field::custom-field.add_field') }}</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-defaultvalue_template" type="text/x-custom-template">
    <div class="line" data-option="defaultvalue">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.default_value') }}</h5>
            <p>{{ trans('custom-field::custom-field.default_value_description') }}</p>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.default_value') }}</h5>
            <input type="text" class="form-control" placeholder="" value="">
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-placeholdertext_template" type="text/x-custom-template">
    <div class="line" data-option="placeholdertext">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.placeholder_text') }}</h5>
            <p>{{ trans('custom-field::custom-field.placeholder_text_description') }}</p>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.placeholder_text') }}</h5>
            <input type="text" class="form-control" placeholder="" value="">
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-defaultvaluetextarea_template" type="text/x-custom-template">
    <div class="line" data-option="defaultvaluetextarea">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.default_value') }}</h5>
            <p>{{ trans('custom-field::custom-field.default_value_description') }}</p>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.default_value') }}</h5>
            <textarea class="form-control" rows="5"></textarea>
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-rows_template" type="text/x-custom-template">
    <div class="line" data-option="rows">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.rows') }}</h5>
            <p>{{ trans('custom-field::custom-field.rows_description') }}</p>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.rows') }}</h5>
            <input type="number" class="form-control" placeholder="Number" min="1" max="10">
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-wysiwygtoolbar_template" type="text/x-custom-template">
    <div class="line" data-option="wysiwygtoolbar">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.toolbar') }}</h5>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.toolbar') }}</h5>
            <select class="form-control">
                <option value="basic">{{ trans('custom-field::custom-field.basic') }}</option>
                <option value="full">{{ trans('custom-field::custom-field.full') }}</option>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-selectchoices_template" type="text/x-custom-template">
    <div class="line" data-option="selectchoices">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.choices') }}</h5>
            <p>{!! trans('custom-field::custom-field.choices_description') !!}</p>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.choices') }}</h5>
            <textarea class="form-control" rows="5"></textarea>
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_options-buttonlabel_template" type="text/x-custom-template">
    <div class="line" data-option="buttonlabel">
        <div class="col-xs-3">
            <h5>{{ trans('custom-field::custom-field.button_label') }}</h5>
        </div>
        <div class="col-xs-9">
            <h5>{{ trans('custom-field::custom-field.button_label') }}</h5>
            <input type="text" class="form-control">
        </div>
        <div class="clearfix"></div>
    </div>
</script>

<script id="_new-field-source_template" type="text/x-custom-template">
    <li class="ui-sortable-handle active">
        <div class="field-column">
            <div class="text col-xs-4 field-label">{{ trans('custom-field::custom-field.new_field') }}</div>
            <div class="text col-xs-4 field-slug"></div>
            <div class="text col-xs-4 field-type">{{ trans('custom-field::custom-field.text') }}</div>
            <a class="show-item-details" href="#"><i class="fa fa-angle-down"></i></a>
            <div class="clearfix"></div>
        </div>
        <div class="item-details">
            <div class="line" data-option="title">
                <div class="col-xs-3">
                    <h5>{{ trans('custom-field::custom-field.field_label') }}</h5>
                    <p>{{ trans('custom-field::custom-field.field_label_description') }}</p>
                </div>
                <div class="col-xs-9">
                    <h5>{{ trans('custom-field::custom-field.field_label') }}</h5>
                    <input type="text" class="form-control" placeholder="" value="New field">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="line" data-option="slug">
                <div class="col-xs-3">
                    <h5>{{ trans('custom-field::custom-field.field_name') }}</h5>
                    <p>{{ trans('custom-field::custom-field.field_name_description') }}</p>
                </div>
                <div class="col-xs-9">
                    <h5>{{ trans('custom-field::custom-field.field_name') }}</h5>
                    <input type="text" class="form-control" placeholder="" value="">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="line" data-option="type">
                <div class="col-xs-3"><h5>{{ trans('custom-field::custom-field.field_type') }}</h5></div>
                <div class="col-xs-9">
                    <h5>{{ trans('custom-field::custom-field.field_type') }}</h5>
                    <select name="" class="form-control change-field-type">
                        <optgroup label="Basic">
                            <option value="text" selected="selected">{{ trans('custom-field::custom-field.field_type_text') }}</option>
                            <option value="textarea">{{ trans('custom-field::custom-field.field_type_textarea') }}</option>
                            <option value="number">{{ trans('custom-field::custom-field.field_type_number') }}</option>
                            <option value="email">{{ trans('custom-field::custom-field.field_type_email') }}</option>
                            <option value="password">{{ trans('custom-field::custom-field.field_type_password') }}</option>
                        </optgroup>
                        <optgroup label="Content">
                            <option value="wysiwyg">{{ trans('custom-field::custom-field.field_type_editor') }}</option>
                            <option value="image">{{ trans('custom-field::custom-field.field_type_image') }}</option>
                            <option value="file">{{ trans('custom-field::custom-field.field_type_file') }}</option>
                        </optgroup>
                        <optgroup label="Choice">
                            <option value="select">{{ trans('custom-field::custom-field.field_type_select') }}</option>
                            <option value="checkbox">{{ trans('custom-field::custom-field.field_type_checkbox') }}</option>
                            <option value="radio">{{ trans('custom-field::custom-field.field_type_radio') }}</option>
                        </optgroup>
                        <optgroup label="Other">
                            <option value="repeater">{{ trans('custom-field::custom-field.field_type_repeater') }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="line" data-option="instructions">
                <div class="col-xs-3">
                    <h5>{{ trans('custom-field::custom-field.field_instruction') }}</h5>
                    <p>{{ trans('custom-field::custom-field.field_instruction_description') }}</p>
                </div>
                <div class="col-xs-9">
                    <h5>{{ trans('custom-field::custom-field.field_instruction') }}</h5>
                    <textarea class="form-control" rows="5"></textarea>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="options">___options___</div>
            <div class="text-right p10">
                <a class="btn red btn-remove" href="#">{{ trans('custom-field::custom-field.remove') }}</a>
                <a class="btn blue btn-close-field" href="#">{{ trans('custom-field::custom-field.close_field') }}</a>
            </div>
        </div>
    </li>
</script>
