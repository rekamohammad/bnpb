<div class="container-fluid diorama-dark">
    @if (!empty(array_filter($post_ids)))
    @php
        $_news = [];
    @endphp
    <div id="list-diorama" class="banner-slider-wraps">
    	@foreach ($post_ids as $catIds)
    		@php
	            $_news[$catIds] = get_posts_by_ids($catIds,5,5);
	        @endphp
	        @if ($_news[$catIds])
    			@foreach ($_news[$catIds] as $feature_item)
				    <div class="slide-item">
                        <a href="{{ route('public.single.detail', $feature_item->slug) }}"
                           title="{{ $feature_item->name }}"
                           style="display: block">
                            <img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                                 style="background-image: url('{{ get_object_image($feature_item->image) }}');">
                        </a>
	                </div>
    			@endforeach
    		@endif
    	@endforeach
	</div>
@endif
</div>
