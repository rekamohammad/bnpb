@extends('bases::layouts.master')
@section('content')
    <ul id="plugin-list">
        @foreach ($list as $plugin)
            <li>
                <p class="plugin-name">{{ $plugin->name }}</p>
                <p class="plugin-description" title="{{ $plugin->description }}">{{ $plugin->description }}</p>
                <p class="plugin-author">{{ trans('bases::system.version') }}: {{ $plugin->version }} | {{ trans('bases::system.author') }}: <a href="{{ $plugin->url }}" target="_blank">{{ $plugin->author }}</a></p>
                <p class="plugin-action">
                    <a class="change_plugin_status" data-plugin="{{ $plugin->path }}" data-status="{{ $plugin->status }}">@if ($plugin->status) {{ trans('bases::system.deactivate') }} @else {{ trans('bases::system.activate') }} @endif</a>
                </p>
            </li>
        @endforeach
    </ul>
@stop
