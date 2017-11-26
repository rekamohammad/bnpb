@if (!empty(array_filter($category_ids)))
	@php
        $_img = true;
    @endphp
    <div class="widget-content green-section">
    	@foreach ($category_ids as $catIds)
		    <div class="tab-pane fade in active">
		        @php
    				$_cats[$catIds] = get_category_by_id($catIds);
		            $_news[$catIds] = get_posts_by_category($catIds,5,5);
		        @endphp
		        @if ($_cats[$catIds])
	            <div class="widget-title">
	            	<span>{{ $_cats[$catIds]->name }}</span>
	        	</div>
				@endif
		        @if (count($_news[$catIds]) > 0)
		            @foreach ($_news[$catIds] as $news_item)
			            <a href="{{ route('public.single.detail', $news_item->slug) }}"
			               title="{{ $news_item->name }}" class="block-has-border">
							@if ($_img)
								<img class="img-full img-bg" src="{{ get_object_image($news_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $news_item->name }}"
                                 style="background-image: url('{{ get_object_image($news_item->image) }}');">
							@endif
			                <span class="post-date">
			                    {{ date('d F Y | H:i', strtotime($news_item->created_at)) }}WIB
			                </span>
			                <span class="post-item"
			                      title="{{ $news_item->name }}">
			                    <h3>{{ $news_item->name }}</h3>
			                </span>
			            </a>
	            		@php
	            	        $_img = false;
	            	    @endphp
		            @endforeach
					@if ($_cats[$catIds])
		            <a href="{{ route('public.single.detail', $_cats[$catIds]->slug) }}" class="block-button">
		                <span class="post-date">
		                    Berita Terbaru Lainnya
		                </span>
		            </a>
					@endif
		        @endif
		    </div>
    	@endforeach
	</div>
@endif

