@if (!empty(array_filter($category_ids)))
	@php
        $_cats = [];
        $_news = [];
        $_tab = 'active';
        $_pane = 'active';
    @endphp

    <ul class="nav nav-tabs">
	    @foreach ($category_ids as $catIds)
	    	@php
	    		$_cats[$catIds] = get_category_by_id($catIds);
		    @endphp
    		@if ($_cats[$catIds])
        	<li class="{{$_tab}}"><a data-toggle="tab" href="#tab{{$catIds}}">{{ $_cats[$catIds]->name }}</a></li>
    		@endif
        	@php
        		$_tab = '';
		    @endphp
	    @endforeach
    </ul>

    <div class="tab-content green-section">
    	@foreach ($category_ids as $catIds)
    		@if ($_cats[$catIds])
			    <div id="tab{{$catIds}}" class="tab-pane fade in {{$_pane}}">
			        @php
			            $_news[$catIds] = get_posts_by_category($catIds,5,5);
			        @endphp
			        @if (count($_news[$catIds]) > 0)
			            @foreach ($_news[$catIds] as $news_item)
			            <a href="{{ route('public.single.detail', $news_item->slug) }}"
			               title="{{ $news_item->name }}" class="block-has-border">
			                <span class="post-date">
			                    {{ date('d F Y | H:i', strtotime($news_item->created_at)) }}WIB
			                </span>
			                <span class="post-item"
			                      title="{{ $news_item->name }}">
			                    <h3>{{ $news_item->name }}</h3>
			                </span>
			            </a>
			            @endforeach
			            <a href="{{ route('public.single.detail', $_cats[$catIds]->slug) }}" class="block-button">
			                <span class="post-date">
			                    Berita Terbaru Lainnya
			                </span>
			            </a>
			        @endif
			    </div>
    		@endif
    		@php
        		$_pane = '';
		    @endphp
    	@endforeach
	</div>
@endif

