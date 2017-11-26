@extends('bases::layouts.master')
@section('content')
    <div class="tabbable-custom tabbable-tabdrop">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_detail" data-toggle="tab">{{ trans('bases::tabs.detail') }}</a>
            </li>
            <li>
                <a href="#tab_settings" data-toggle="tab">{{ trans('language::language.settings') }}</a>
            </li>
            {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TABS, null, LANGUAGE_MODULE_SCREEN_NAME) !!}
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_detail">
                <div class="row">
                    <div class="col-md-5">
                        @php do_action(BASE_ACTION_META_BOXES, 'language', 'top') @endphp
                        <div class="main-form">
                            <div class="form-wrap">
                                <div class="form-group">
                                    <input type="hidden" id="language_flag_path" value="{{ BASE_LANGUAGE_FLAG_PATH }}">
                                    <label for="language_id" class="control-label">{{ trans('language::language.choose_language') }}</label>
                                    <select id="language_id" class="form-control select-search-full">
                                        <option>{{ trans('language::language.select_language') }}</option>
                                        @foreach ($languages as $key => $language)
                                            <option value="{{ $key }}" data-language="{{ json_encode($language) }}"> {{ $language[2] }} - {{ $language[1] }}</option>
                                        @endforeach
                                    </select>
                                    {!! Form::helper(trans('language::language.choose_language_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="lang_name" class="control-label required">{{ trans('language::language.full_name') }}</label>
                                    <input id="lang_name" type="text" class="form-control">
                                    {!! Form::helper(trans('language::language.full_name_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="lang_locale" class="control-label required">{{ trans('language::language.locale') }}</label>
                                    <input id="lang_locale" type="text" class="form-control">
                                    {!! Form::helper(trans('language::language.locale_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="lang_code" class="control-label">{{ trans('language::language.language_code') }}</label>
                                    <input id="lang_code" type="text" class="form-control">
                                    {!! Form::helper(trans('language::language.language_code_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="lang_text_direction" class="control-label">{{ trans('language::language.text_direction') }}</label>
                                    <p>
                                        <label><input name="lang_rtl" class="lang_is_ltr" type="radio" value="0" checked="checked"> {{ trans('language::language.left_to_right') }}</label> &nbsp;
                                        <label><input name="lang_rtl" class="lang_is_rtl" type="radio" value="1"> {{ trans('language::language.right_to_left') }}</label>
                                    </p>
                                    {!! Form::helper(trans('language::language.text_direction_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="flag_list" class="control-label">{{ trans('language::language.flag') }}</label>
                                    <select id="flag_list" class="form-control select-search-language">
                                        <option></option>
                                        @foreach ($flags as $key => $flag)
                                            <option value="{{ $key }}">{{ $flag }}</option>
                                        @endforeach
                                    </select>
                                    {!! Form::helper(trans('language::language.flag_helper')) !!}
                                </div>

                                <div class="form-group">
                                    <label for="lang_order" class="control-label">{{ trans('language::language.order') }}</label>
                                    <input id="lang_order" type="number" value="0" class="form-control">
                                    {!! Form::helper(trans('language::language.order_helper')) !!}
                                </div>
                                <input type="hidden" id="lang_id" value="0">
                                <p class="submit">
                                    <button class="btn btn-primary" id="btn-language-submit">{{ trans('language::language.add_new_language') }}</button>
                                </p>
                            </div>
                        </div>
                        @php do_action(BASE_ACTION_META_BOXES, 'language', 'advanced') @endphp
                    </div>
                    <div class="col-md-7">
                        <div class="table-responsive">
                            <table class="table table-hover table-language">
                                <thead>
                                <tr>
                                    <th class="text-left"><span>{{ trans('language::language.full_name') }}</span></th>
                                    <th><span>{{ trans('language::language.locale') }}</span></th>
                                    <th><span>{{ trans('language::language.code') }}</span></th>
                                    <th><span>{{ trans('language::language.default_language') }}</span></th>
                                    <th><span>{{ trans('language::language.order') }}</span></th>
                                    <th><span>{{ trans('language::language.flag') }}</span></th>
                                    <th><span>{{ trans('language::language.actions') }}</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($active_languages as $item)
                                    @include('language::partials.language-item', compact('item'))
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_settings">
                {!! Form::open(['route' => 'languages.settings']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <br>
                            <div class="form-group @if ($errors->has('language_hide_default')) has-error @endif">
                                {!! Form::onOff('language_hide_default', old('language_hide_default', setting('language_hide_default', false))) !!}
                                <label for="language_hide_default">{{ trans('language::language.language_hide_default') }}</label>
                            </div>
                            <div class="form-group @if ($errors->has('language_display')) has-error @endif">
                                <label for="language_display">{{ trans('language::language.language_display') }}</label>
                                {!! Form::select('language_display', ['all' => trans('language::language.language_display_all'), 'flag' => trans('language::language.language_display_flag_only'), 'name' => trans('language::language.language_display_name_only')], setting('language_display', 'all'), ['class' => 'select-full', 'id' => 'language_display']) !!}
                            </div>

                            <div class="form-group @if ($errors->has('language_switcher_display')) has-error @endif">
                                <label for="language_switcher_display">{{ trans('language::language.switcher_display') }}</label>
                                {!! Form::select('language_switcher_display', ['dropdown' => trans('language::language.language_switcher_display_dropdown'), 'list' => trans('language::language.language_switcher_display_list')], setting('language_switcher_display', 'dropdown'), ['class' => 'select-full', 'id' => 'language_switcher_display']) !!}
                            </div>

                            <div class="form-group @if ($errors->has('language_hide_languages')) has-error @endif">
                                <label for="language_hide_languages">{{ trans('language::language.hide_languages') }}</label>
                                <p><small>{{ trans('language::language.hide_languages_description') }}</small></p>
                                <ul class="list-item-checkbox">
                                    @foreach (Language::getActiveLanguage() as $language)
                                        @if (!$language->lang_is_default)
                                            <li>
                                                <input type="checkbox" class="icheck" name="language_hide_languages[]" value="{{ $language->lang_id }}" id="language_hide_languages_item-{{ $language->lang_code }}" @if (in_array($language->lang_id, json_decode(setting('language_hide_languages', '[]'), true))) checked="checked" @endif>
                                                <label for="language_hide_languages_item-{{ $language->lang_code }}">{{ $language->lang_name }}</label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                {!! Form::helper(trans_choice('language::language.hide_languages_helper_display_hidden', count(json_decode(setting('language_hide_languages', '[]'), true)), ['language' => Language::getHiddenLanguageText()])) !!}
                            </div>

                            <div class="text-left">
                                <button type="submit" name="submit" value="save" class="btn btn-info">
                                    <i class="fa fa-save"></i> {{ trans('bases::forms.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        var BLanguage = BLanguage || {};

        BLanguage.routes = {
            set_default: '{{ route('languages.set.default') }}',
            get_language: '{{ route('languages.get') }}',
            store: '{{ route('languages.store') }}',
            edit: '{{ route('languages.edit') }}'
        };
    </script>
@stop
