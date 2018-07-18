<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>{{ page_title()->getTitle() }}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <link rel="icon shortcut" href="{{ url('/images/favicon.png') }}">

        @foreach ($stylesheets as $style)
            {!! HTML::style($style) !!}
        @endforeach

        @foreach ($headScripts as $script)
            @if (is_array($script))
                {!! HTML::script($script['url']) !!}
                @if ($script['fallback'])
                    <script>window.{!! $script['fallback'] !!} || document.write('<script src="{{ $script['fallbackURL'] }}"><\/script>')</script>
                @endif
            @else
                {!! HTML::script($script) !!}
            @endif
        @endforeach

        <script src="{{ url('vendor/media/packages/dropzone/dropzone.js') }}"></script>

        @if (array_key_exists($active_theme, $themes))
            {!! HTML::style($themes[$active_theme] . '?v=' . time()) !!}
        @endif

        @yield('head')
    </head>

    <body class="@yield('body-class')" id="@yield('body-id', 'module')">

        <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        @yield('page')


        @include('bases::elements.common')

        @foreach ($bodyScripts as $script)
            {!! HTML::script($script) !!}
        @endforeach

        @yield('javascript')

        @stack('footer')

    </body>

</html>
