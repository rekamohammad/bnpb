
{!! Theme::partial('header') !!}

@if (Route::currentRouteName() == 'public.index')
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
@else
    <main class="main" id="main">
    <div class="container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-2">
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
            <div class="col-md-10">
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