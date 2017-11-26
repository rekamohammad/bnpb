@extends('bases::layouts.master')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-magic-wand"></i> {{ trans('theme::theme.theme') }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row pad">
                        @foreach(ThemeManager::getThemes() as $key =>  $theme)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="thumbnail">
                                    <div class="img-thumbnail-wrap" style="background-image: url('{{ url(config('theme.themeDir')) }}/{{ $key }}/screenshot.png')"></div>
                                    <div class="caption">
                                        <div class="row">
                                            <div class="col-md-12" style="word-break: break-all">
                                                <h4>{{ $theme['name'] }}</h4>
                                                <p>{{ trans('theme::theme.author') }}: {{ array_get($theme, 'author') }}</p>
                                                <p>{{ trans('theme::theme.version') }}: {{ array_get($theme, 'version') }}</p>
                                                <p>{{ trans('theme::theme.description') }}: {{ array_get($theme, 'description') }}</p>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div>
                                                @if (setting('theme') == $key)
                                                    <a href="#" class="btn btn-danger" disabled="disabled"><i class="fa fa-check"></i> {{ trans('theme::theme.activated') }}</a>
                                                @else
                                                    <a href="{{ route('theme.active', [$key]) }}" class="btn btn-primary">{{ trans('theme::theme.active') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop