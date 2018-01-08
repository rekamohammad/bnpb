
{!! Theme::partial('header') !!}

@if (Route::currentRouteName() == 'public.index')
    @php
        $detect = new App\MobileDetect\Mobile_Detect;
    @endphp
    @if ($detect->isMobile())
    <main class="main" id="main">
        <div class="container">
            <div class="main-index">
                <div class="row">
                    <div class="col-md-12">
                        @php
                            echo Theme::partial('post-newest');
                        @endphp
                        <a href="{{ url('/berita') }}" class="block-button">
                            <span class="post-date">
                            Berita Terbaru Lainnya
                            </span>
                        </a><br>
                    </div>
                    <div class="col-md-12">
                        @php
                            echo Theme::partial('post-announcement');
                        @endphp
                        <a href="{{ url('/pengumuman') }}" class="block-button">
                            <span class="post-date">
                            Pengumuman Terbaru Lainnya
                            </span>
                        </a><br>
                    </div>
                    <div class="col-md-12">
                        @php
                            echo Theme::partial('post-popular');
                        @endphp
                        <a href="{{ url('/berita') }}" class="block-button">
                            <span class="post-date">
                            Berita Terpopuler Lainnya
                            </span>
                        </a><br>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @else
    <main class="main" id="main">
        <div class="container">
            <div class="main-index">
                <div class="row">
                    <div class="col-md-3">
                        @php
                            echo Theme::partial('post-popular');
                            echo Theme::partial('mountain-status', ['category_ids' => explode(',', theme_option('mountain-status'))]);
                        @endphp
                        <div class="dynamic-sidebar">
                            {!! dynamic_sidebar('home_left') !!}
                        </div>
                    </div>
                    <div class="col-md-6 middle-widget">
                        @php
                            echo Theme::partial('post-slide', ['category_ids' => explode(',', theme_option('home-slider-feed'))]);
                            echo Theme::partial('post-tab', ['category_ids' => explode(',', theme_option('home-tabbed-feed'))]);
                        @endphp
                        <div class="dynamic-sidebar no-title">
                            @php
                                echo Theme::partial('post-midpanel');
                            @endphp
                        </div>
                    </div>
                    <div class="col-md-3">
                        @php
                            echo Theme::partial('post-video', ['category_ids' => explode(',', theme_option('home-right-feed'))]);
                        @endphp
                        <div class="dynamic-sidebar">
                            {!! dynamic_sidebar('home_right') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endif
@elseif (Request::segment(1) == 'diorama' || Request::segment(1) == 'diorama.html')
    <main class="main" id="main">
    <div id="main-diorama" class="container-fluid">
        <div class="row">
            <!-- <div class="col-md-12 middle-widget"> -->
                @php
                    $news_id = get_diorama()[0]['attributes']['id'];
                    if($news_id){
                        echo Theme::partial('slide-diorama', ['category_ids' => [$news_id]]);
                        echo Theme::partial('post-diorama-middle', ['post_ids' => explode(',', theme_option('diorama-middle'))]);
                        echo Theme::partial('post-diorama', ['category_ids' => [$news_id]]);
                    }
                @endphp
            <!-- </div> -->
        </div>
    </div>
    </main>
@elseif (Request::segment(1) == 'berita')
    <main class="main" id="main">
    <div class="container">
    <div class="main-index">
        <div class="row">
            {{-- <div class="col-md-2">
                <div class="calender">
                    <div class="row">
                        <div class="col-md-12 col-xs-6">
                            <div class="col-calender" style="">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                                <center>    
                                    <h3>{{ date('d') }}</h3>
                                    <p>{{ date('Y-m') }}</p>
                                </center>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-6">
                            <div class="col-share">
                                <h3>Share With :</h3>
                                <div class="col-sosmed">
                                    <div class="sharethis-inline-share-buttons"></div>
                                    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=59c69294c28d0800122e1b44&product=inline-share-buttons"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="dynamic-sidebar">
                    @php
                        echo Theme::partial('post-popular');
                        echo Theme::partial('mountain-status', ['category_ids' => explode(',', theme_option('mountain-status'))]);
                    @endphp
                        {!! dynamic_sidebar('home_left') !!}
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        {!! Theme::breadcrumb()->render() !!}
                    </div>
                    {!! Theme::content() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </main>
@elseif (Request::segment(1) == 'gpr')
    <main class="main" id="main">
    <div class="container">
    <div class="main-index">
        <div class="row">
            <div class="col-md-3">
                @php
                    echo Theme::partial('post-popular');
                    echo Theme::partial('mountain-status', ['category_ids' => explode(',', theme_option('mountain-status'))]);
                @endphp
            </div>
            <div class="col-md-6">
                <div id="gpr-kominfo-widget-container"></div>
            </div>
            <div class="col-md-3">
                @php
                    echo Theme::partial('post-video', ['category_ids' => explode(',', theme_option('home-right-feed'))]);
                    $infografis_id = get_infografis_rekapitulasi_bencana()[0]['attributes']['id'];
                    if($infografis_id) { 
                        echo Theme::partial('post-infografis', ['category_ids' => [$infografis_id]]);
                    }
                @endphp
            </div>
        </div>
    </div>
    </div>
    </main>
@elseif (Request::segment(1) == 'publikasi')
    <main class="main" id="main">
    <div class="container">
        <div class="main-index">
            <div class="row">
                @if (Request::segment(2) == 'poster')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/poster') }}" title="Publikasi BNPB">Publikasi Poster</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_poster()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'leaflet')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/leaflet') }}" title="Publikasi BNPB">Publikasi Leaflet</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_leaflet()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'buku-data-bencana')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/buku-data-bencana') }}" title="Publikasi BNPB">Publikasi Buku BNPB</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_buku_bnpb()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                    
                @elseif (Request::segment(2) == 'siaga-bencana')
                    <div class="row">
                        <div class="col-md-8">
                            <a href="{{ url('/publikasi/siaga-bencana') }}" target="_self"> <button class="button-tab active"> Siaga Bencana </button> </a>
                            <a href="{{ url('/publikasi/rekapitulasi-bencana') }}" target="_self"> <button class="button-tab"> Rekapitulasi Bencana </button> </a>
                            <a href="{{ url('/publikasi/kejadian-bencana') }}" target="_self"> <button class="button-tab"> Kejadian Bencana </button> </a>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/siaga-bencana') }}" title="Publikasi BNPB">Publikasi Siaga Bencana</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_siaga_bencana()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'rekapitulasi-bencana')
                    <div class="row">
                        <div class="col-md-8">
                            <a href="{{ url('/publikasi/siaga-bencana') }}" target="_self"> <button class="button-tab"> Siaga Bencana </button> </a>
                            <a href="{{ url('/publikasi/rekapitulasi-bencana') }}" target="_self"> <button class="button-tab active"> Rekapitulasi Bencana </button> </a>
                            <a href="{{ url('/publikasi/kejadian-bencana') }}" target="_self"> <button class="button-tab"> Kejadian Bencana </button> </a>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/rekapitulasi-bencana') }}" title="Publikasi BNPB">Publikasi Rekapitulasi Bencana</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_rekapitulasi_bencana()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'kejadian-bencana')
                    <div class="row">
                        <div class="col-md-8">
                            <a href="{{ url('/publikasi/siaga-bencana') }}" target="_self"> <button class="button-tab"> Siaga Bencana </button> </a>
                            <a href="{{ url('/publikasi/rekapitulasi-bencana') }}" target="_self"> <button class="button-tab"> Rekapitulasi Bencana </button> </a>
                            <a href="{{ url('/publikasi/kejadian-bencana') }}" target="_self"> <button class="button-tab active"> Kejadian Bencana </button> </a>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/kejadian-bencana') }}" title="Publikasi BNPB">Publikasi Kejadian Bencana</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_kejadian_bencana()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'kajian-bencana')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/kajian-bencana') }}" title="Publikasi BNPB">Publikasi Kajian Bencana</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_kajian_bencana()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'jurnal')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/jurnal') }}" title="Publikasi BNPB">Publikasi Jurnal</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_jurnal()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @elseif (Request::segment(2) == 'atlas')
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/atlas') }}" title="Publikasi BNPB">Publikasi Atlas</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis_atlas()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @else
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/semua-publikasi') }}" title="Publikasi BNPB">Semua Publikasi BNPB</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_publikasi()[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @endif
                
            </div>
        </div>
    </div>
    </main>
@elseif (Request::segment(1) == 'infografis')
    @if (Request::segment(2) == 'detail')
    <main class="main" id="main">
    <div class="container">
        <div class="main-index">
            <div class="row">
                {!! Theme::content() !!}
                </div>
            </div>
        </div>
    </div>
    </main>
    @else
    <div class="col-md-12">
        <h3 class="block-title"><span><a href="{{ url('/publikasi/semua-publikasi') }}" title="Publikasi BNPB">Semua Publikasi BNPB</a></span></h3>
    </div>
    @php
        $infografis_id = get_publikasi()[0]['attributes']['id'];
        if($infografis_id) { 
            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
        }
    @endphp
    @endif
@elseif (Request::segment(1) == 'undang-undang.html' || Request::segment(1) == 'peraturan-presiden.html' || Request::segment(1) == 'keputusan-presiden.html' || Request::segment(1) == 'keputusan-menteri.html' ||  Request::segment(1) == 'pembentukan-bpbd.html' || Request::segment(1) == 'peraturan-pemerintah.html')
    <main class="main" id="main">
    <div class="container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-3">
                <nav class="nav-sidebar">
                     {!!
                        Menu::generateMenu([
                            'slug' => 'left-menu',
                            'options' => ['class' => 'nav'],
                            'view' => 'main-menu'
                        ])
                    !!}
                </nav>  
            </div>
           {{--  <div class="col-md-3">
                <div class="dynamic-sidebar">
                    @php
                        echo Theme::partial('post-popular');
                        echo Theme::partial('mountain-status', ['category_ids' => explode(',', theme_option('mountain-status'))]);
                    @endphp
                        {!! dynamic_sidebar('home_left') !!}
                </div>
            </div> --}}
            <div class="col-md-9 middle-widget">
                <div class="row">
                    <div class="col-md-12">
                        {!! Theme::breadcrumb()->render() !!}
                    </div>
                    {!! Theme::content() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </main>
	
@elseif (Request::segment(1) == 'galleries')
	<main class="main" id="main">
	<div class="container">
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					{!! Theme::content() !!}
				</div>
			</div>
		</div>
	</div>
	</main>
	
@else
    <main class="main" id="main">
    <div class="container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-3">
                <div class="calender">
                    <div class="row">
                        <div class="col-md-12 col-xs-6">
                            <div class="col-calender" style="">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                                <center>    
                                    <h3>{{ date('d') }}</h3>
                                    <p>{{ date('Y-m') }}</p>
                                </center>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-6">
                            <div class="col-share">
                                <h3>Share With :</h3>
                                <div class="col-sosmed">
                                    <div class="sharethis-inline-share-buttons"></div>
                                    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=59c69294c28d0800122e1b44&product=inline-share-buttons"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
           {{--  <div class="col-md-3">
                <div class="dynamic-sidebar">
                    @php
                        echo Theme::partial('post-popular');
                        echo Theme::partial('mountain-status', ['category_ids' => explode(',', theme_option('mountain-status'))]);
                    @endphp
                        {!! dynamic_sidebar('home_left') !!}
                </div>
            </div> --}}
            <div class="col-md-9 middle-widget">
                <div class="row">
                    <div class="col-md-12">
                        {!! Theme::breadcrumb()->render() !!}
                    </div>
                    {!! Theme::content() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </main>
@endif

{!! Theme::partial('footer') !!}