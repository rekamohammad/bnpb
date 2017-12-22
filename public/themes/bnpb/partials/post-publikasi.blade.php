<div class="panel-category">
	@foreach ($category_ids as $catIds)
		@php
			$_infografis = get_posts_by_category($catIds,16,16);
		@endphp
	@endforeach
	@if (count($_infografis) > 0)
		@foreach ($_infografis as $infografis_item)
			<div class="col-md-3 col-xs-6">
				<article class="featured-small box-news no-square">
					<a href="{{ url('/'.$infografis_item->slug) }}" title="{{ $infografis_item->name }}">
						<img class="img-responsive" src="{{ get_object_image($infografis_item->image) }}" alt="{{ $infografis_item->name }}">
					</a>
					<header class="featured-header">
						<h2><a href="{{ url('/'.$infografis_item->slug) }}" title="{{ $infografis_item->name }}">{{ $infografis_item->name }}</a></h2>
						<p class="simple-share">
							<span class="article-date"> {{ $infografis_item->created_at }}</span>
						</p>
					</header>
				</article>
			</div>
		@endforeach
		<div class="col-md-12 col-xs-12">
			<center>{!! $_infografis->links() !!}</center>
		</div>
		
	@else
		<span> Artikel Tentang Infografis Belum Tersedia </span>
	@endif
	<div class="clearfix"></div>
</div>