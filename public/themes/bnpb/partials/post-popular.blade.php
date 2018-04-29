@php
    $_img = true;
@endphp
<div class="widget-content green-section">
	<div class="tab-pane fade in active">
		@php
			$_news = get_popular_posts(5);
		@endphp
		<div class="widget-title">
			<span>{{ __('Berita Populer') }}</span>
		</div>
		@if (count($_news) > 0)
			@foreach ($_news as $news_item)
				<a href="{{ route('public.single.detail', $news_item->slug) }}" data-id="{{ $news_item->id }}"
				title="{{ $news_item->name }}" class="block-has-border post-track">
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
		@endif
	</div>
</div>

