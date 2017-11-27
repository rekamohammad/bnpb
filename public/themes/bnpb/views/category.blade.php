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
@else
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
                                    <div class="col-md-4">
                                        <a href="{{ route('public.single.detail', $post->slug) }}" title="{{ $post->name }}">
                                            <img class="img-full img-bg" src="{{ get_object_image($post->image, 'medium') }}" style="background-image: url('{{ get_object_image($post->image) }}');" alt="{{ $post->name }}">
                                        </a>
                                    </div>
                                    <div class="col-md-8">
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
                {!! $posts->links() !!}
            </nav>
        @endif
    </div>
    <div class="col-md-4">
        {!! Theme::partial('sidebar') !!}
    </div>
@endif