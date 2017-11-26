@if (!empty(array_filter($category_ids)))
    @php
        $_news = [];
    @endphp
    <div class="banner-slider-wrap">
    	@foreach ($category_ids as $catIds)
    		@php
	            $_news[$catIds] = get_posts_by_category($catIds,5,5);
	        @endphp
	        @if ($_news[$catIds])
    			@foreach ($_news[$catIds] as $feature_item)
				    <div class="slide-item">
                        <a href="{{ route('public.single.detail', $feature_item->slug) }}"
                           title="{{ $feature_item->name }}"
                           style="display: block">
                            <img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                                 style="background-image: url('{{ get_object_image($feature_item->image) }}');">
                            <span class="slide-item-link"
                                  title="{{ $feature_item->name }}">
                                <span class="post-date">
				                    {{ date('d F Y | H:i', strtotime($feature_item->created_at)) }}WIB
				                </span>
                                <span><h3>{{ $feature_item->name }}</h3></span>
                            </span>
                        </a>
	                </div>
    			@endforeach
    		@endif
    	@endforeach
	</div>
@endif

