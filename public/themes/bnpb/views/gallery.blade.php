

<div class="col-md-12">
    <div class="page-content">
        <article class="post post--single">
            <div class="post__content">
                <div id="list-photo">
                    @foreach (gallery_meta_data($gallery->id, 'gallery') as $image)
                        @if ($image)
                            <div class="item photo-item" data-fancybox data-src="{{ url(array_get($image, 'img')) }}" data-sub-html="{{ array_get($image, 'description') }}">
                                <div class="photo-item">
                                    <div class="thumb">
                                        <img src="{{ url(array_get($image, 'img')) }}" alt="{{ array_get($image, 'description') }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <hr/>

                <div id="list-thumb">
                    @foreach (gallery_meta_data($gallery->id, 'gallery') as $image)
                        @if ($image)
                            <div class="item photo-thumb" >
                                <img src="{{ url(array_get($image, 'img')) }}" alt="{{ array_get($image, 'description') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <hr/>
                <h1 class="article-content-title">{{ $gallery->name }}</h1>
                <div class="article-date">
                    <span class="post-date">
                        {{ date('d F Y | H:i', strtotime($gallery->created_at)) }}WIB
                    </span>
                </div>
                <p>
                    {{ $gallery->description }}
                </p>
            </div>
        </article>
    </div>
</div>