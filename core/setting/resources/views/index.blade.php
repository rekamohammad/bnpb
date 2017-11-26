@extends('bases::layouts.master')
@section('content')
    {!! Form::open(['route' => ['settings.edit']]) !!}
    <div class="tabbable-custom tabbable-tabdrop">
        <ul class="nav nav-tabs" id="settings-tab">
            @foreach ($settings as $tab_id => $tab)
                <li @if ($loop->first) class="active" @endif>
                    <a data-toggle="tab" href="#{{ $tab_id }}">{{ $tab['name'] }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" style="overflow: inherit">
            @foreach ($settings as $tab_id => $tab)
                <div class="tab-pane @if ($loop->first) active @endif" id="{{ $tab_id }}">
                    @foreach ($tab['settings'] as $key => $setting)
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has($setting['attributes']['name'])) has-error @endif">
                            {!! Form::label($setting['attributes']['name'], $setting['label'], ['class' => 'control-label']) !!}
                            {!! Setting::render($setting) !!}
                            @if (array_key_exists('helper', $setting))
                                <span class="help-block">{!! $setting['helper'] !!}</span>
                            @endif
                            {!! Form::error($setting['attributes']['name'], $errors) !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    @endforeach
                </div>
            @endforeach
            <div class="clearfix"></div>
            <div class="text-center">
                <button type="submit" name="submit" value="save" class="btn btn-info">
                    <i class="fa fa-save"></i> {{ trans('bases::forms.save') }}
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
