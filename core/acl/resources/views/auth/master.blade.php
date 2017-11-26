@extends('bases::layouts.base')

@section('body-class')
    login
@stop

@section ('page')
    <div class="content">
        @yield('content')
    </div>
    <div class="copyright"> {!! trans('bases::layouts.copyright', ['year' => Carbon::now()->format('Y'), 'company' => config('cms.base_name'), 'version' => config('cms.version')]) !!} </div>
@stop
