<div class="aside-box">
    <div class="aside-box-header">
        <h4>{{ __($config['name']) }}</h4>
    </div>

    <div class="aside-box-content">
        <div class="img-maps popup-info">
            @if (! is_null(get_siaga_bencana_post()))
            <a href="{{ get_siaga_bencana_post()->image }}" data-sub-html="<h4>{{ get_siaga_bencana_post()->title }}</h4><p>{{ get_siaga_bencana_post()->description }}.</p>" >
                <img src="{{ get_siaga_bencana_post()->image }}" class="img-responsive" style="width:100%;"/>
            </a>
            @else
            <a href="/uploads/24/2017-09-27-sosialisasi-hadapi-gunung-agung-smprnsiang.jpeg" data-sub-html="<h4> Infografis Siaga Bencana </h4><p> Sosialisasi Menghadapi Letusan Gunung Agung.</p>" >
                <img src="/uploads/24/2017-09-27-sosialisasi-hadapi-gunung-agung-smprnsiang.jpeg" class="img-responsive" style="width:100%;">
            </a>
            @endif
        </div>
        <br>
        <div>
            <center>
                <strong>
                    <a href="{{ url('/publikasi/siaga-bencana') }}">Selengkapnya &gt;&gt;</a>
                </strong>
            </center>
        </div>
    </div> 
</div>