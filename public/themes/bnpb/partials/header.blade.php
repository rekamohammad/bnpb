<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="canonical" href="{{ url('/') }}">
    <meta http-equiv="content-language" content="en">
    <link rel="shortcut icon" href="{{ theme_option('favicon') }}">

    {!! Theme::header() !!}
    <link href="https://fonts.googleapis.com/css?family=Bad+Script" rel="stylesheet">
    <link href="/themes/bnpb/assets/css/lightgallery.css" rel="stylesheet">
	<link href="/themes/bnpb/assets/css/galericustom.css" rel="stylesheet">
    <style>
      @media (max-width: 767px) {
	body .middle-widget .nav-tabs>li {width: 100%;}
	body .footer .panel.panel-default {width: 100%;}
	body .footer .panel.panel-default .footer-grids {margin-top: 38px;}
	body .footer > .container > .row > .col-md-2.col-sm-6{display:none;}
	body .footer > .container > .row > .col-md-2.col-sm-6:last-child{display:block;}
      }
    </style>
    @php
        $_current = explode('.',Route::currentRouteName());
    @endphp
</head>
<body class="{{ $_current[1] }}">
    <div class="wrapper" id="site_wrapper">
        <header class="header" id="header">
            <div class="header-wrap">
                <nav class="nav-top">
                    <div class="container">
                        <div class="pull-right">
                            <!-- <ul class="pull-left">
                                @if (acl_check_login())
                                    <li><a href="{{ route('public.account.overview') }}"><i class="fa fa-user"></i> <span>{{ acl_get_current_user()->getFullName() }}</span></a></li>
                                    <li><a href="{{ route('public.access.logout') }}"><i class="fa fa-sign-out"></i> {{ __('Logout') }}</a></li>
                                @else
                                    <li><a href="{{ route('public.access.login') }}"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a></li>
                                @endif
                            </ul> -->
                            
                        </div>
                    </div>
                </nav>
                <div class="header-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="logo">
                                    <a href="{{ url('/') }}" title="{{ setting('site_title') }}">
                                        <img src="{{ url(theme_option('logo')) }}" alt="{{ setting('site_title') }}">
                                    </a>
                                </h1>
                            </div>
                            <div class="col-md-6 maintain-md">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <div class="pull-left">
                                                <div class="pull-right">
                                                    <div class="language-wrapper">
                                                        {!! apply_filters('language_switcher') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
                                                <a href="{{ setting('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                                                <a href="{{ setting('twitter') }}" title="Twitter" class="hi-icon fa fa-twitter"></a>
                                                <a href="https://www.instagram.com/bnpb_indonesia/" title="Instagram" class="hi-icon fa fa-instagram"></a>
                                                <a href="https://www.youtube.com/user/BNPBIndonesia/" title="Youtube" class="hi-icon fa fa-youtube-play"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 maintain-search">
                                        <div class="pull-right">
                                            <form class="navbar-form navbar-right" role="search"
                                                  accept-charset="UTF-8"
                                                  action="{{ route('public.search') }}"
                                                  method="GET">
                                                <div class="tn-searchtop">
                                                    <button type="button" class="btn btn-default js-btn-searchtop">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <button type="submit" class="btn btn-default">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="{{ __('Search news...') }}" name="q">
                                                    </div>
                                                </div>
                                                <button id="tn-searchtop" class="js-btn-searchtop" type="button"><i class="fa fa-search"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- <div class="header-content-right">
                                <p><img alt="Banner" src="{{ url(theme_option('top_banner', '/themes/newstv/assets/images/banner.png')) }}" style="width: 728px; height: 90px;"></p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-default" role="navigation">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand"  href="{{ url('/') }}" title="{{ setting('site_title') }}">
                            <img src="{{ url(theme_option('logo')) }}" alt="{{ setting('site_title') }}">
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">

                        {!!
                            Menu::generateMenu([
                                'slug' => 'main-menu',
                                'options' => ['class' => 'nav navbar-nav'],
                                'view' => 'main-menu'
                            ])
                        !!}

                        
                    </div>
                </div>
            </nav>

            @if (Request::segment(1) == 'diorama' || Request::segment(1) == 'diorama.html')
            @else
                <section class="header-hotnews">
                    <div class="container">
                       <!--  <div class="hotnews-content">
                            <h2 class="hotnews-tt"><i class="fa fa-bullhorn"></i> {{ __('Flash News') }}</h2>
                            <div class="hotnews-dv">
                                <div class="hotnews-slideshow">
                                    <div class="js-marquee">
                                        @foreach (get_featured_posts(5) as $feature_item)
                                        <a href="{{ route('public.single.detail', $feature_item->slug) }}"
                                           title="{{ $feature_item->name }}">{{ $feature_item->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div> !-->
                    </div>
                </section>
            @endif  
        </header>

