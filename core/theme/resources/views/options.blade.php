@extends('bases::layouts.master')
@section('content')
    <div id="theme-option-header">
        <div class="display_header">
            <h2>{{ __('Theme options') }}</h2>
            @if (ThemeOption::getArg('debug') == true) <span class="theme-option-dev-mode-notice">{{ __('Developer Mode Enabled') }}</span>@endif
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="theme-option-intro-text">{{ __('This is the theme option for :theme theme, please make sure that you can control what you changes.', ['theme' => studly_case(setting('theme'))]) }}</div>
    <div class="theme-option-container">
        <div class="theme-option-sidebar">
            <ul class="nav nav-tabs tab-in-left">
                @foreach(ThemeOption::constructSections() as $section)
                    <li @if ($loop->first) class="active" @endif>
                        <a href="#tab_{{ $section['id'] }}" data-toggle="tab">@if (!empty($section['icon']))<i class="{{ $section['icon'] }}"></i> @endif {{ $section['title']  }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="theme-option-main">
            {!! Form::open(['route' => 'theme.options']) !!}
            <div class="tab-content tab-content-in-right">
                <div class="theme-option-sticky">
                    <div class="info_bar">
                        <div class="theme-option-action_bar">
                            <span class="fa fa-spin fa-spinner hidden"></span>
                            <input type="submit" class="btn btn-primary" value="Save Changes">
                            {{--<input type="submit" class="btn btn-info" value="Reset Section">
                            <input type="submit" class="btn btn-info" value="Reset All">--}}
                        </div>
                    </div>
                </div>
                @foreach(ThemeOption::constructSections() as $section)
                    <div class="tab-pane @if ($loop->first) active @endif" id="tab_{{ $section['id'] }}">
                        @foreach (ThemeOption::constructFields($section['id']) as $field)
                            <div class="form-group @if ($errors->has($field['attributes']['name'])) has-error @endif">
                                {!! Form::label($field['attributes']['name'], $field['label'], ['class' => 'control-label']) !!}
                                {!! ThemeOption::renderField($field) !!}
                                @if (array_key_exists('helper', $field))
                                    <span class="help-block">{!! $field['helper'] !!}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="theme-option-sticky sticky-bottom">
                    <div class="info_bar">
                        <div class="theme-option-action_bar">
                            <span class="fa fa-spin fa-spinner hidden"></span>
                            <input type="submit" class="btn btn-primary" value="Save Changes">
                            {{--<input type="submit" class="btn btn-info" value="Reset Section">
                            <input type="submit" class="btn btn-info" value="Reset All">--}}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
