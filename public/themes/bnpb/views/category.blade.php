@if($category->slug == 'pengumuman' || $category->slug == 'announcement')
    <div class="col-md-12">
        <section class="main-box category-box main-index">
            <div class="main-box-content">
                <div class="box-style box-style-3">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="col-md-4">
                                @php
                                    $_content = do_shortcode($post->content);
                                    $_content = preg_match('/(<embed .*?>)/', $_content, $img_tag);
                                @endphp
                                <div class="block-announment panel panel-default panel-shadow">
                                    <div class="panel-heading">{{ $category->name }}</div>
                                    <div class="panel-body">
                                        <a href="{{ route('public.single.detail', $post->slug) }}"
                                           title="{{ $post->name }}">
                                            <span class="post-item"
                                                  title="{{ $post->name }}">
                                                <h3>{{ $post->name }}</h3>
                                                
                                            </span>
                                        </a>
                                        @php
                                            $_link = '';
                                            $_content = explode('[pdf-file]', $post->content);
                                            if(isset($_content[1])){
                                                $_link = explode('[/pdf-file]', $_content[1])[0];
                                            }
                                        @endphp
                                        @if (!empty($_link))
                                        <div class="btn-download">
                                            <a href="{{ $_link }}" target="_blank" class="btn btn-warning btn-block">DOWNLOAD</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12">
                            <br/><p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($posts->count() > 0)
            <nav class="pagination-wrap">
                {!! $posts->links() !!}
            </nav>
        @endif
    </div> 
@elseif($category->slug == 'diorama')
    <div class="col-md-12">
        <section class="main-box category-box main-index">
            <div class="main-box-content">
                <div class="box-style box-style-3">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="col-md-4">
                                @php
                                    $_content = do_shortcode($post->content);
                                    $_content = preg_match('/(<embed .*?>)/', $_content, $img_tag);
                                @endphp
                                <div class="block-announment panel panel-default panel-shadow">
                                    <div class="panel-heading">{{ $category->name }}</div>
                                    <div class="panel-body">
                                        <a href="{{ route('public.single.detail', $post->slug) }}"
                                           title="{{ $post->name }}">
                                            <span class="post-item"
                                                  title="{{ $post->name }}">
                                                <h3>{{ $post->name }}</h3>
                                                
                                            </span>
                                        </a>
                                        @php
                                            $_link = '';
                                            $_content = explode('[pdf-file]', $post->content);
                                            if(isset($_content[1])){
                                                $_link = explode('[/pdf-file]', $_content[1])[0];
                                            }
                                        @endphp
                                        @if (!empty($_link))
                                        <div class="btn-download">
                                            <a href="{{ $_link }}" target="_blank" class="btn btn-warning btn-block">DOWNLOAD</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12">
                            <br/><p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($posts->count() > 0)
            <nav class="pagination-wrap">
                {!! $posts->links() !!}
            </nav>
        @endif
    </div> 
@elseif($category->slug == 'berita')
    <div class="col-md-8">
        <section class="main-box category-box main-index">
            <div class="main-box-header">
                <h2>
                    {{ $category->name }}
                </h2>
            </div>
            <div class="main-box-content">

                <div class="box-style box-style-3">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="media-news block-has-border">
                                <div class="row">
                                 <!--   <div class="col-md-4">
                                        <a href="{{ route('public.single.detail', $post->slug) }}" title="{{ $post->name }}">
                                            <img class="img-full img-bg" src="{{ get_object_image($post->image, 'medium') }}" style="background-image: url('{{ get_object_image($post->image) }}');" alt="{{ $post->name }}">
                                        </a>
                                    </div> !-->
                                    <div class="col-md-12">
                                        <a href="{{ route('public.single.detail', $post->slug) }}"
                                           title="{{ $post->name }}">
                                            <span class="post-date">
                                                {{ date('d F Y | H:i', strtotime($post->created_at)) }}WIB
                                            </span>
                                            <span class="post-item"
                                                  title="{{ $post->name }}">
                                                <h3>{{ $post->name }}</h3>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12">
                            <br/><p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($posts->count() > 0)
            <nav class="pagination-wrap">
		@php
		$path = $_SERVER['REQUEST_URI'];
		$folders = explode('/', $path);
		$getSplit =  explode('?',$folders[2]);
		@endphp 	
                {!! $posts->setPath($getSplit[0]) !!}
            </nav>
        @endif
    </div>
    {{-- <div class="col-md-4">
        {!! Theme::partial('sidebar') !!}
    </div> --}}
    <div class="col-md-4" style="margin-top: -70px;">
        @php
            echo Theme::partial('post-video', ['category_ids' => explode(',', theme_option('home-right-feed'))]);
        @endphp
        <div class="dynamic-sidebar">
            {!! dynamic_sidebar('home_right') !!}
        </div>
    </div>
@elseif($category->slug == 'undang-undang' || $category->slug == 'peraturan-presiden' || $category->slug == 'peraturan-pemerintah' || $category->slug == 'keputusan-presiden' || $category->slug == 'keputusan-menteri' || $category->slug == 'pembentukan-bpbd')
        <div class="col-md-12">
        <section class="main-box category-box main-index">
            <div class="main-box-header">
                <h2>
                    {{ $category->name }}
                </h2>
            </div>
          {{--   <div class="col-md-12 maintain-search">
                <div class="pull-right">
                    <form class="navbar-form navbar-right" role="search"
                          accept-charset="UTF-8"
                          action="{{ route('public.search') }}"
                          method="GET">
                        <div class="tn-searchtop">
                            <button type="button" class="btn btn-default js-btn-searchtop">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ __('Search...') }}" name="q">
                            </div>
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <button id="tn-searchtop" class="js-btn-searchtop" type="button"><i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div> --}}
            <div class="main-box-content">

                <div class="box-style box-style-3">
                    @if ($posts->count() > 0)
                        <div class="scroller">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Tentang</th>
                                    <th>Download</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $post)
                                    @php
                                        $_content = do_shortcode($post->content);
                                        $_content = preg_match('/(<embed .*?>)/', $_content, $img_tag);
                                    @endphp
                                    @php
                                            $_link = '';
                                            $_content = explode('[pdf-file]', $post->content);
                                            if(isset($_content[1])){
                                                $_link = explode('[/pdf-file]', $_content[1])[0];
                                            }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td><a href="{{ route('public.single.detail', $post->slug) }}" target="_blank">{{ string_limit_words($post->name, 55) }}</a></td>
                                        <td>{{ ($post->description) }}</td>
                                        @php
                                        $_content = do_shortcode($post->content);
                                        $_content = preg_match('/(<embed .*?>)/', $_content, $img_tag);
                                        @endphp
                                        @php
                                                $_link = '';
                                                $_content = explode('[pdf-file]', $post->content);
                                                if(isset($_content[1])){
                                                    $_link = explode('[/pdf-file]', $_content[1])[0];
                                                }
                                        @endphp
                                        @if (!empty($_link))
                                        <td><a href="{{ $_link }}" target="_blank" class="btn btn-warning btn-block">DOWNLOAD</a></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="col-xs-12">
                            <br/><p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($posts->count() > 0)
            <nav class="pagination-wrap">
        @php
        $path = $_SERVER['REQUEST_URI'];
        $folders = explode('/', $path);
        $getSplit =  explode('?',$folders[2]);
        @endphp     
                {!! $posts->setPath($getSplit[0]) !!}
            </nav>
        @endif
    </div>
@else
    <div class="col-md-12">
        <section class="main-box category-box main-index">
            <div class="main-box-header">
                <h2>
                    {{ $category->name }}
                </h2>
            </div>
            <div class="main-box-content">

                <div class="box-style box-style-3">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="media-news block-has-border">
                                <div class="row">
                                 <!--   <div class="col-md-4">
                                        <a href="{{ route('public.single.detail', $post->slug) }}" title="{{ $post->name }}">
                                            <img class="img-full img-bg" src="{{ get_object_image($post->image, 'medium') }}" style="background-image: url('{{ get_object_image($post->image) }}');" alt="{{ $post->name }}">
                                        </a>
                                    </div> !-->
                                    <div class="col-md-12">
                                        <a href="{{ route('public.single.detail', $post->slug) }}"
                                           title="{{ $post->name }}">
                                            <span class="post-date">
                                                {{ date('d F Y | H:i', strtotime($post->created_at)) }}WIB
                                            </span>
                                            <span class="post-item"
                                                  title="{{ $post->name }}">
                                                <h3>{{ $post->name }}</h3>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12">
                            <br/><p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @if ($posts->count() > 0)
            <nav class="pagination-wrap">
        @php
        $path = $_SERVER['REQUEST_URI'];
        $folders = explode('/', $path);
        $getSplit =  explode('?',$folders[2]);
        @endphp     
                {!! $posts->setPath($getSplit[0]) !!}
            </nav>
        @endif
    </div>
@endif
