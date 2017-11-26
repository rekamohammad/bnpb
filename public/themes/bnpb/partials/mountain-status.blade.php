@if (!empty(array_filter($category_ids)))

    <div class="widget-content green-section">
    	@foreach ($category_ids as $catIds)
		    <div class="tab-pane fade in active">
		        @php
		        	$_awas = true;
		        	$_waspada = true;
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
		            	@php
		            	    $_status = '';
		            	    $_content = explode('[status-alert]', $news_item->content);
		            	    if(isset($_content[1])){
		            	        $_status = explode('[/status-alert]', $_content[1])[0];
		            	        $_status = strtolower($_status);
		            	    }
		            	@endphp
		            	@if (!empty($_status) && $_status == 'awas')
		            		@if ($_awas)
			            		<div class="status-label status-danger">
			            			<span class="btn-danger"><strong>AWAS</strong></span>
			            		</div>
							@endif
				            <a href="{{ route('public.single.detail', $news_item->slug) }}"
				               title="{{ $news_item->name }}" class="block-has-border">
				                <span class="post-date">
				                    {{ date('d F Y | H:i', strtotime($news_item->updated_at)) }}WIB
				                </span>
				                <span class="post-item"
				                      title="{{ $news_item->name }}">
				                    <h3>{{ $news_item->name }}</h3>
				                </span>
				            </a>
        			        @php
        			        	$_awas = false;
        			        @endphp
						@endif
		            @endforeach

		            @foreach ($_news[$catIds] as $news_item)
		            	@php
		            	    $_status = '';
		            	    $_content = explode('[status-alert]', $news_item->content);
		            	    if(isset($_content[1])){
		            	        $_status = explode('[/status-alert]', $_content[1])[0];
		            	        $_status = strtolower($_status);
		            	    }
		            	@endphp
		            	@if (!empty($_status) && $_status == 'waspada')
		            		@if ($_waspada)
			            		<div class="status-label status-warning">
			            			<span class="btn-warning"><strong>WASPADA</strong></span>
			            		</div>
							@endif
				            <a href="{{ route('public.single.detail', $news_item->slug) }}"
				               title="{{ $news_item->name }}" class="block-has-border">
				                <span class="post-date">
				                    {{ date('d F Y | H:i', strtotime($news_item->updated_at)) }}WIB
				                </span>
				                <span class="post-item"
				                      title="{{ $news_item->name }}">
				                    <h3>{{ $news_item->name }}</h3>
				                </span>
				            </a>
        			        @php
        			        	$_waspada = false;
        			        @endphp
						@endif
		            @endforeach
					@if ($_cats[$catIds])
		            <a href="{{ route('public.single.detail', $_cats[$catIds]->slug) }}" class="block-button">
		                <span class="post-date">
		                    Selengkapnya..
		                </span>
		            </a>
					@endif
		        @endif
		    </div>
    	@endforeach
	</div>
@endif

