@foreach ($category_ids as $catIds)
<div class="widget-content diorama-section">
	<div class="container">
    @php
		$_news = get_posts_by_category($catIds,8,8);
    @endphp
    @if (count($_news) > 0)
    	<div class="row">
	        @foreach ($_news as $news_item)
	        <div class="col-md-3 col-xs-6">
               	<div class="diorama-container">
					<img class="img-full img-bg" src="{{ get_object_image($news_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $news_item->name }}" style="background-image: url('{{ get_object_image($news_item->image) }}');">
					<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#{{ $news_item->slug }}"><span>{{ $news_item->name }}</span></button>
                </div>

				<div id="{{ $news_item->slug }}" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
			        		<div class="modal-body">
			        			<div class="row">
			        				<div class="col-sm-7">
			        					<img class="img-full img-bg" src="{{ get_object_image($news_item->image, 'featured') }}" alt="{{ $news_item->name }}" style="background-image: url('{{ get_object_image($news_item->image) }}');">
			        				</div>
			        				<div class="modal-text col-sm-5">
			        					<div class="modal-header">
		          							<button type="button" class="close" data-dismiss="modal">×</button>
		          						</div>
			        					<h2>{{ $news_item->name }}</h2>
			        					<p>{{ $news_item->descrption }}</p>
			        					<div class="related">
			        						<h2>Foto Terkait :</h2>
			        						<div class="row">
				        						@foreach (gallery_meta_data($news_item->id, 'post') as $image)
							                        @if ($image)
							                            <div class="col-xs-3">
							                                <img src="{{ url(array_get($image, 'img')) }}" alt="{{ array_get($image, 'description') }}" class="img-responsive">
							                            </div>
							                        @endif
							                    @endforeach
							                </div>
			        					</div>
			        					<div class="sosmed-share">
			        						<h2>Share With :</h2>
			        						<ul>
			        							<li>
			        								<a href="https://twitter.com/intent/tweet?url={{ route('public.single.detail', $news_item->slug) }}&text={{ $news_item->name }}">
				        								<i class="fa fa-twitter"></i>
				        							</a>
			        							</li>
			        							<li>
			        								<a href="https://www.facebook.com/sharer.php?u={{ route('public.single.detail', $news_item->slug) }}">
				        								<i class="fa fa-facebook"></i>
				        							</a>
			        							</li>
			        						</ul>
			        					</div>
			        				</div>
			        			</div>
				        	</div>
				        </div>

					</div>
				</div>
	        </div>
	        @endforeach
	    </div>
    @endif
	</div>
</div>
@endforeach

