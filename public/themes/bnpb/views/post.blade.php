<div class="col-md-12">
    <section class="main-box">
    <div class="main-box-content">
        <div class="article-image">
            <img class="img-full img-bg" src="{{ get_object_image($post->image, 'featured') }}" alt="{{ $post->name }}"
             style="background-image: url('{{ get_object_image($post->image) }}');">
        </div>
        @if (!$post->tags->isEmpty())
            <div class="tags-wrap">
                @foreach ($post->tags as $tag)
                    <span>
                        <a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a>
                    </span>
                @endforeach
            </div>
        @endif

        <h1 class="article-content-title">{{ $post->name }}</h1>

        <div class="article-date">
            <span class="post-date">
                {{ date('d F Y | H:i', strtotime($post->created_at)) }}WIB
            </span>
        </div>

        <div class="article-content">
            {!! $post->content !!}
        </div>
        

        <div class="post-meta">
        <!-- <span><i class="fa fa-user"></i> {{ $post->user->getFullName() }}</span>
            <span><i class="fa fa-calendar"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</span> -->
            @if (!$post->categories->isEmpty())
                <!-- <span>
                    <i class="fa fa-list"></i> <a href="{{ route('public.single.detail', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                </span> -->
            @endif
        </div>
        
        @if(!empty(theme_option('facebook-app-id')))
        <div class="comment-post">
            <h4 class="article-content-subtitle">
                {{ __('Comments') }}
            </h4>
            <div class="facebook-comment">
                @if (defined('COMMENT_MODULE_SCREEN_NAME'))
                    <div id="my-comments"></div>
                    {!! render_comment_block('#my-comments', Request::url()) !!}
                @else
                    <div class="fb-comments" data-href="{{ Request::url() }}" data-numposts="5"></div>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>
<section class="main-box">
    <div class="main-box-header">
        <h2><i class="fa fa-leaf"></i> {{ __('Related posts') }}</h2>
    </div>
    <div class="main-box-content">
        <div class="box-style box-style-4">
            @foreach (get_related_posts($post->slug, 6, $post->views) as $related_item)
                <div class="media-news">
                    <a href="{{ route('public.single.detail', $related_item->slug) }}" title="{{ $related_item->name }}" class="media-news-img">
                        <img class="img-full img-bg" src="{{ get_object_image($related_item->image) }}" style="background-image: url('{{ get_object_image($related_item->image) }}');" alt="{{ $related_item->name }}">
                    </a>
                    <div class="media-news-body">
                        <p class="common-title">
                            <a href="{{ route('public.single.detail', $related_item->slug) }}" title="{{ $related_item->name }}">
                                {{ $related_item->name }}
                            </a>
                        </p>
                        <p class="common-date">
                            <time datetime="">{{ date_from_database($post->created_at, 'M d, Y') }}</time>
                        </p>
                        <div class="common-summary">
                            {{ $related_item->description }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>