@if (!empty(array_filter($category_ids)))
    @php
        $_infografis = [];
	@endphp
	<div class="widget-content green-section">
		<div class="tab-pane fade in active">
			<div class="widget-title">
				<span>Infografis</span>
			</div>
			<div class="banner-slider-wrap">
				@foreach ($category_ids as $catIds)
					@php
						$_infografis[$catIds] = get_posts_by_category($catIds,4,4);
					@endphp
					@if ($_infografis[$catIds])
						@foreach ($_infografis[$catIds] as $infografis_item)
							<div class="slide-item">
								<a href="#"
								title="{{ $infografis_item->name }}"
								style="display: block">
									<img class="img-full img-bg" src="{{ get_object_image($infografis_item->image) }}" alt="{{ $infografis_item->name }}"
										style="background-image: url('{{ get_object_image($infografis_item->image) }}');">
									<span class="slide-item-link"
										title="{{ $infografis_item->name }}">
										<span class="post-date">
											{{ date('d F Y | H:i', strtotime($infografis_item->created_at)) }}WIB
										</span>
										<span><h3>{{ $infografis_item->name }}</h3></span>
									</span>
								</a>
							</div>
						@endforeach
					@endif
				@endforeach
			</div>
		</div>
	</div>
@endif

