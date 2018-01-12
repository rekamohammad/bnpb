<h3 class="block-title"><span><a href="{{ url('/diorama/') }}" title="Diorama BNPB">Album Diorama</a></span></h3>
@php
	$listAlbum = get_album();
@endphp
<div class="row widget-content diorama-section">
	<div class="container">
    @if (! empty($listAlbum))
    	<div class="list-diorama row">
			@foreach ($listAlbum as $album)
	        <div class="col-md-3 col-xs-6">
               	<div class="diorama-container">
					<img class="img-full img-bg" src="{{ get_object_image($album->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $album->name }}" style="background-image: url('{{ get_object_image($album->image) }}');">
					<button type="button" class="btn btn-info btn-lg btn-popup" data-toggle="modal" data-target="#{{ $album->slug }}"><span>{{ $album->name }}</span></button>
                </div>
	        </div>
			@endforeach
		</div>

		@foreach ($listAlbum as $album)
			@php
				$_diorama = get_diorama_by_album($album->id);
			@endphp
		<div id="{{ $album->slug }}" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="row">
							<div class="col-md-11">
								<h3 class="block-title"><span>{{ $album->name }}</span></h3>
							</div>
							<div class="col-md-1">
								<button type="button" class="close" data-dismiss="modal">×</button>
							</div>
						</div>
					</div>
					<div class="modal-body">
						@if (count($_diorama) > 0)
						<div class="row">
							<div class="col-md-12 modal-margin">
								<div class="slider slider-single col-md-12">
									@foreach ($_diorama as $diorama_item)
										<div>
											<div class="col-md-7">
												@if ($diorama_item->format_type == 'images')
													<img class="img-full img-bg" src="{{ get_object_image($diorama_item->content) }}" alt="{{ $diorama_item->name }}" style="background-image: url('{{ get_object_image($diorama_item->image) }}');">
												@elseif ($diorama_item->format_type == 'video')
													<iframe class="embed-responsive-item" allowfullscreen frameborder="0" height="315" width="420" src="{{ $diorama_item->content }}"></iframe>
												@else
													<iframe class="embed-responsive-item" allowfullscreen frameborder="0" height="315" width="420" src="{{ str_replace('watch?v=', 'embed/', $diorama_item->content) }}"></iframe>
												@endif
											</div>
											<div class="modal-text col-md-5">
												<h4> {{ $diorama_item->description }} </h4>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 modal-margin slider-nav">
								@foreach ($_diorama as $diorama_item)
									<div class="col-md-3 col-xs-6">
										<img class="img-full img-bg" src="{{ get_object_image($diorama_item->image) }}" alt="{{ $diorama_item->name }}" style="background-image: url('{{ get_object_image($diorama_item->image) }}');"></span>
									</div>
								@endforeach
							</div>
						</div>
						@else
							<div class="col-md-12 col-xs-12">
								<center><h4><strong><span> Konten diorama tidak ditemukan </span></strong></h4><br></center>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		@endforeach
	@endif
	</div>
</div>

