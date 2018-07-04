@php
    $_img = true;
@endphp
<div class="col-md-12">
	<div class="page-content">
		<div class="row">
			{!! Theme::partial('kebencanaan-menu') !!}
			<div class="col-md-9">
				<!-- start content !-->
				@if (! empty(get_definisi_bencana()))
					@if (empty(get_definisi_bencana()->image))
						<img class="img-full img-bg" style="margin: 0 15px 0 0" src="{{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }}" alt="{{ get_definisi_bencana()->name }}" style="background-image: {{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }};">
					@else
						@if (file_exists(public_path(get_definisi_bencana()->image)))
							<img class="img-full img-bg" src="{{ get_object_image(get_definisi_bencana()->image) }}" alt="{{ get_definisi_bencana()->name }}" style="background-image: url('{{ get_object_image(get_definisi_bencana()->image) }}');">
						@else
							<img class="img-full img-bg" src="{{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }}" alt="{{ get_definisi_bencana()->name }}" style="background-image: {{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }};">
						@endif
					@endif

					<br><br>

					<div class="col-md-3" style="float: right;  text-align:right;">
						<div class="row">
							<div class="col-md-12">
								<p class="font12 fontnormal fontarial"><label style="margin-right: 2px;">Share : </label>
									<a style="text-decoration: none;" href="http://www.facebook.com/share.php?u=/pengetahuan-bencana" target="_blank" rel="nofollow noopener noreferrer"><img src="/uploads/24/facebook.png" width="20" height="20" /></a> | 
									<a href="https://twitter.com/intent/tweet?text=Definisi dan Jenis Bencana" target="_blank"><img src="/uploads/24/twitter.png" alt="Tweet" width="19" height="19" /></a> | 
									<a href="whatsapp://send?text=Definisi dan Jenis Bencana  {{ urlencode(url()->current()) }}" data-action="share/whatsapp/share" target="_blank"><img src="/uploads/24/whatsapp.png" alt="Tweet" width="19" height="19" /></a>
								</p>
							</div>
						</div>
					</div>

					<div class="name">
						<h1><strong>{{ get_definisi_bencana()->name }}</strong></h1>
					</div>
					{!! get_definisi_bencana()->content !!}
				@else
					<center><span> No Article </span></center>
				@endif
				<!-- end start content !-->
			</div>
		</div>
	</div>
</div>