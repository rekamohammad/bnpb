@extends('bases::layouts.base')

@section('body-class') full-width page-condensed @stop

@section('page')

    @include('bases::layouts.partials.top-header')

    @yield('content')

    <!-- Footer -->
    <div class="footer clearfix center-block row">
        <div class="col-xs-12 col-sm-8">{!! trans('bases::layouts.copyright') !!}</div>

        <div class="hidden-xs col-sm-4 text-right">
            <strong>{{ trans('bases::layouts.powered_by') }}</strong>
            <a href="http://www.botble.com"><img src="{{ url('/images/logos/logo.png') }}"/></a>
        </div>
    </div>
    <!-- /footer -->
@stop
