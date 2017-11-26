
{!! Theme::partial('header') !!}
<h1>mbuut</h1>

<main class="main" id="main">
    <div class="container">
        @if (Route::currentRouteName() == 'public.index')
            @php
                $featured = get_featured_posts(5);
            @endphp
            @if (count($featured) > 0)
                <div class="main-feature">
                    @foreach ($featured as $feature_item)
                        <div class="feature-item">
                            <div class="feature-item-dv">
                                <a href="{{ route('public.single.detail', $feature_item->slug) }}"
                                   title="{{ $feature_item->name }}"
                                   style="display: block">
                                    <img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                                         style="background-image: url('{{ get_object_image($feature_item->image) }}');">
                                    <span class="feature-item-link"
                                          title="{{ $feature_item->name }}">
                                        <span>{{ $feature_item->name }}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="main-content">
            <div class="row">
                <div class="col-md-2">
                    <div class="calender">
                        <div class="col-calender" style="">
                            <span class="pull-left"></span>
                            <span class="pull-right"></span>
                            <div class="clearfix"></div>
                            <center>    
                                <h3>{{ date('d') }}</h3>
                                <p>{{ date('Y-m') }}</p>
                            </center>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-share">
                            <h3>Share With :</h3>
                            <div class="col-sosmed">
                                <ul>
                                    <li><img src="images/fb.png"><span>255</span></li>
                                    <li><img src="images/twiit.png"><span>197</span></li>
                                    <li><img src="images/ig.png"><span>15</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Theme::breadcrumb()->render() !!}
                        </div>
                        <div class="col-md-8">
                            {!! Theme::content() !!}
                        </div>
                        <div class="col-md-4">
                            {!! Theme::partial('sidebar') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}

