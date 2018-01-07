@if (!empty(array_filter($category_ids)))
    <div class="widget-content green-section" style="margin-bottom:10px">
    	@foreach ($category_ids as $catIds)
		    <div class="tab-pane fade in active">
		        @php
    				$_cats[$catIds] = get_category_by_id($catIds);
		            $_news[$catIds] = get_posts_by_category($catIds,1,1);
		        @endphp
		        @if (count($_news[$catIds]) > 0)
		            @foreach ($_news[$catIds] as $news_item)
    			        @php
    			        	$url = '';
    	    				$url = explode("[/youtube-video]", $news_item->content);
    	    				if($url[0]){
    	    					$url = explode("[youtube-video]", $url[0]);
    	    					$url = $url[1];
    	    				}
    			        @endphp
		            	<div class="embed-responsive embed-responsive-16by9">
					        <iframe class="embed-responsive-item" allowfullscreen frameborder="0" height="315" width="420" src="{{ str_replace('watch?v=', 'embed/', $url) }}"></iframe>
					    </div>
		            @endforeach
					@if ($_cats[$catIds])
		             <!-- <a href="{{ route('public.single.detail', $_cats[$catIds]->slug) }}" class="dotted-button"> !-->
					 <a href="http://www.bnpbindonesia.tv/front/article/detail/video.php/read/article15402630092016" class="dotted-button">
		                <span class="post-date">
		                    Berita Video Lainnya
		                </span>
		            </a>
					@endif
		        @endif
		    </div>
    	@endforeach
	</div>
@endif