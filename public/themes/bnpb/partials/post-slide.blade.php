@php
	$limit =1;
@endphp
@if(count(get_List_Slider($limit))>0)
	<div class="banner-slider-wrap">
	@foreach (get_List_Slider(6) as $feature_item)
		<div class="slide-item">
			<a href="#" title="{{ $feature_item->name }}" style="display: block">
				<img class="img-full img-bg" src="{{ get_object_image($feature_item->images, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
					 style="height: 370px; background-image: url('{{ get_object_image($feature_item->images) }}');">
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
	</div>
@else

	@if (!empty(array_filter($category_ids)))
		@php
			$_news = [];
		@endphp
		<div class="banner-slider-wrap">
			@foreach ($category_ids as $catIds)
				@php
					$_news[$catIds] = get_posts_by_category_slide($catIds,6 ,4);
				@endphp
				@if ($_news[$catIds])
					@foreach ($_news[$catIds] as $feature_item)
						<div class="slide-item">
							<a href="#" title="{{ $feature_item->name }}" style="display: block">
								<img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
									 style="height: 370px; background-image: url('{{ get_object_image($feature_item->image) }}');">
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

@endif