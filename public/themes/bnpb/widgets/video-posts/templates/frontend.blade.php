<div class="aside-box youtube-box">
    <div class="aside-box-content">
        @foreach(get_popular_posts($config['number_display'], ['where' => ['featured' => 1, 'status' => 1, 'format_type' => 'video']]) as $post)
            <div class="media-news media-video">
                <a href="{{ route('public.single.detail', $post->slug) }}"
                   class="media-news-img" title="{{ $post->name }}">
                    <img class="img-full img-bg" src="{{ get_object_image($post->image, 'thumb') }}"
                         style="background-image: url('{{ get_object_image($post->image) }}');"
                         alt="{{ $post->name }}">
                </a>
                <!-- <div class="media-news-body">
                    <p class="common-title">
                        <a href="{{ route('public.single.detail', $post->slug) }}"
                           title="{{ $post->name }}">{{ $post->name }}</a>
                    </p>
                    <p class="common-date">
                        <time datetime="">{{ date_from_database($post->created_at, 'M d, Y') }}</time>
                    </p>
                </div> -->
            </div>
        @endforeach
    </div>
    @php
        $_cat = get_category_by_id(46);
    @endphp
    @if (count($_cat) > 0)
        <a href="{{ route('public.single.detail', $_cat->slug) }}" class="dotted-button">
            <span class="video-link">
                <strong>Berita Video Lainnya</strong>
            </span>
        </a>
    @endif
</div>

