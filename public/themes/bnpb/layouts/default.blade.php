
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
                            $_img = true;
                        @endphp
                        <div class="widget-content green-section">
                            <div class="tab-pane fade in active">
                                @php
                                    $_news = get_popular_posts(3);
                                @endphp
                                <div class="widget-title">
                                    <span>Berita Populer</span>
                                </div>
                                @if (count($_news) > 0)
                                    @foreach ($_news as $news_item)
                                        <a href="{{ route('public.single.detail', $news_item->slug) }}" data-id="{{ $news_item->id }}"
                                        title="{{ $news_item->name }}" class="block-has-border post-track">
                                            @if ($_img)
                                                <img class="img-full img-bg" src="{{ get_object_image($news_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $news_item->name }}"
                                                style="background-image: url('{{ get_object_image($news_item->image) }}');">
                                            @endif
                                            <span class="post-date">
                                                {{ date('d F Y | H:i', strtotime($news_item->created_at)) }}WIB
                                            </span>
                                            <span class="post-item"
                                                title="{{ $news_item->name }}">
                                                <h3>{{ $news_item->name }}</h3>
                                            </span>
                                        </a>
                                        @php
                                            $_img = false;
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @php
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
@elseif (Request::segment(1) == 'diorama')
    <main class="main" id="main">
        <div class="container">
            @php
                $news_id = get_diorama()[0]['attributes']['id'];
                if($news_id){
                    echo Theme::partial('slide-diorama', ['category_ids' => [$news_id]]);
                    echo Theme::partial('post-diorama-middle', ['category_ids' => [$news_id]]);
                    echo Theme::partial('post-diorama', ['category_ids' => [$news_id]]);
                }
            @endphp
        </div>
    </main>
@elseif (Request::segment(1) == 'struktur')
    <main class="main" id="main">
        <div class="container">
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/jquery.atooltip.min.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/organisasi.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/d3.min.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/orgchart.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/orgchart_common.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/orgchart_container.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/orgchart_events.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/orgchart_renderer.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/layout_grid.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/layout_hierarchy.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/layout_main.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/layout_tree.js"></script>
        <script type="text/javascript" src="/themes/bnpb/assets/orgChart/layout_footer.js"></script>
        <script type="text/javascript" src="/uploads/json/data.js"></script>
        <div class="col-md-12">
            <div class="row">
                <div id="content ">
                    <div id="unit-kerja"> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12">
            <div class="row">
                <div id="content unit-kerja">
                    <div id="struktur"></div>
                </div>
            </div>
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
                    $infografis_id = get_infografis('rekapitulasi-bencana')[0]['attributes']['id'];
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
                @if (Request::segment(2) == 'siaga-bencana')
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
                        $infografis_id = get_infografis(Request::segment(2))[0]['attributes']['id'];
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
                        $infografis_id = get_infografis(Request::segment(2))[0]['attributes']['id'];
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
                        $infografis_id = get_infografis(Request::segment(2))[0]['attributes']['id'];
                        if($infografis_id) { 
                            echo Theme::partial('post-publikasi', ['category_ids' => [$infografis_id]]);
                        }
                    @endphp
                @else
                    <div class="col-md-12">
                        <h3 class="block-title"><span><a href="{{ url('/publikasi/'.Request::segment(2)) }}" title="Publikasi BNPB">Publikasi @php echo str_replace("-", " ", trim(Request::segment(2)," ")) @endphp</a></span></h3>
                    </div>
                    @php
                        $infografis_id = get_infografis(Request::segment(2))[0]['attributes']['id'];
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
@elseif (Request::segment(1) == 'undang-undang.html' || Request::segment(1) == 'peraturan-presiden.html' || Request::segment(1) == 'keputusan-presiden.html' || Request::segment(1) == 'keputusan-menteri.html' ||  Request::segment(1) == 'pembentukan-bpbd.html' || Request::segment(1) == 'peraturan-pemerintah.html' || Request::segment(1) == 'peraturan-kepala-bnpb.html')
    <main class="main" id="main">
    <div class="container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <h3 class="block-title"><span><a href="{{ url('/undang-undang.html') }}" title="Publikasi BNPB">Link Produk Hukum</a></span></h3>
            </div>
            <div class="col-md-3">
                <nav class="undang-sidebar">
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
@elseif (Request::segment(1) == 'nasionals')
    
	<main class="main" id="main">
	<div class="container">
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					{!! Theme::partial('post-nasionals') !!}
				</div>
			</div>
		</div>
	</div>
	</main>
@elseif (Request::segment(1) == 'internasionals')
    
	<main class="main" id="main">
	<div class="container">
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					{!! Theme::partial('post-internasionals') !!}
				</div>
			</div>
		</div>
	</div>
	</main>	
	
@elseif (Request::segment(1) == 'bpbd-provinsi')
    
	<main class="main" id="main">
	<div class="container">
		<div class="main-content">
			<div class="row">
				<div class="col-md-12">
					{!! Theme::partial('post-bpbd-provinsi') !!}
				</div>
			</div>
		</div>
	</div>
	</main>		
@elseif (Request::segment(1) == 'bpbd-kabupaten')
    
	<main class="main" id="main">
	<div class="container">
		<div class="main-content">
			<div class="row">
				<div class="col-md-12"> 
					{!! Theme::partial('post-bpbd-kabupaten') !!}
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