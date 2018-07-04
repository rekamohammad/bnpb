<div id="column-left" class="col-md-3">
    <div class="box">
        <div class="box-content box-category">
            <ul id="accordion-category" class="accordion">
                <li class="panel default">
                    <a class="clearfix" href="{{ url('/definisi-bencana') }}">
                        <strong>Definisi dan Jenis Bencana</strong>
                    </a>
                </li>
                <li class="panel">
                    <a class="clearfix" href="{{ url('/potensi-bencana') }}">
                        <strong>Potensi dan Ancaman Bencana</strong>
                    </a>
                </li>
                <li class="panel">
                    <a class="clearfix" href="{{ url('/penanggulangan-bencana') }}">
                        <strong>Sistem Penanggulangan Bencana</strong>
                    </a>
                </li>
                <li class="panel">
                    <a class="clearfix" href="{{ url('/publikasi/siaga-bencana') }}">
                        <strong>Siaga Bencana</strong>
                    </a>
                </li>
            </ul>
        </div>
		@if (! empty(get_announcement_bencana()))
        <div id="kotakbencana" class="box-content box-category">
            <div class="col-md-12">
                <div id="kotakgambar" class="image">
					@if (empty(get_announcement_bencana()->image))
					<img src="{{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }}" width="240px" alt="{{ get_announcement_bencana()->name }}" style="background-image: {{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }};">
					@else
						@if (file_exists(public_path(get_announcement_bencana()->image)))
							<img src="{{ get_object_image(get_announcement_bencana()->image) }}" width="240px" alt="{{ get_announcement_bencana()->name }}" style="background-image: url('{{ get_object_image(get_announcement_bencana()->image) }}');">
						@else
							<img src="{{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }}" width="240px" alt="{{ get_announcement_bencana()->name }}" style="background-image: {{ url('/uploads/24/folder-baru/bnpb-thumbnail-default-150x150.jpg') }};">
						@endif
					@endif
					<br><br>
                    {!! get_announcement_bencana()->content !!}
                </div>
            </div>
        </div>
		@endif
    </div>
</div>
