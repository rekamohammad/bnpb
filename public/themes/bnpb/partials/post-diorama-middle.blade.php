<h3 class="block-title"><span><a href="{{ url('/diorama/') }}" title="Diorama BNPB">Diorama Terbaru</a></span></h3>
@foreach ($category_ids as $catIds)
<div class="row widget-content diorama-section">
	@php
		$_news = get_posts_by_category($catIds,10,10);
	@endphp
	<div class="list-diorama banner-slider-wraps">
		@foreach ($_news as $feature_item)
			@if ($feature_item->format_type == 'video')
				<div style="display:none;" id="{{ $feature_item->format_type }}_{{ $feature_item->id }}">
					<video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none">
						<source src="{{ $feature_item->content }}" type="video/mp4">
						Your browser does not support HTML5 video.
					</video>
				</div>
			@endif
			<div class="col-md-3 col-xs-6">
				<div class="diorama-container slide-item popup-info">
					@if ($feature_item->format_type == 'video')
						<a data-poster="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" data-sub-html="<h4>{{ $feature_item->title }}</h4> <p>{{ $feature_item->description }}.</p>" data-html="#{{ $feature_item->format_type }}_{{ $feature_item->id }}">
							<img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}" style="background-image: url('{{ get_object_image($feature_item->image) }}');">
						</a>
					@else
						<a href="{{ $feature_item->content }}" data-sub-html="<h4>{{ $feature_item->title }}</h4> <p>{{ $feature_item->description }}.</p>" >
							<img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}" style="background-image: url('{{ get_object_image($feature_item->image) }}');">
						</a>
					@endif
				</div>
			</div>
		@endforeach
	</div>
</div>
@endforeach
