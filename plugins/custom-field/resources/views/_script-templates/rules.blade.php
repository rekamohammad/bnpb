<script type="text/x-custom-template" id="rules_group_template">
    <div class="line rule-line mb10">
        <select class="form-control pull-left rule-a">
            @foreach($ruleGroups as $key => $row)
                <optgroup label="{{ $key or '' }}">
                    @foreach($row['items'] as $item)
                        <option value="{{ $item['slug'] or '' }}">{{ $item['title'] or '' }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <select class="form-control pull-left rule-type">
            <option value="==">{{ trans('custom-field::custom-field.is_equal_to') }}</option>
            <option value="!=">{{ trans('custom-field::custom-field.is_not_equal_to') }}</option>
        </select>
        <div class="rules-b-group pull-left">
            @foreach($ruleGroups as $key => $row)
                @foreach($row['items'] as $item)
                    <select class="form-control rule-b" data-rel="{{ $item['slug'] or '' }}">
                        @foreach($item['data'] as $keyData => $rowData)
                            <option value="{{ $keyData or '' }}">{{ $rowData or '' }}</option>
                        @endforeach
                    </select>
                @endforeach
            @endforeach
        </div>
        <a class="location-add-rule-and location-add-rule btn btn-info pull-left" href="#">{{ trans('custom-field::custom-field.and') }}</a>
        <a href="#" class="remove-rule-line"><span>&nbsp;</span></a>
        <div class="clearfix"></div>
    </div>
</script>
