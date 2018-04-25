<div class="aside-box">
    <div class="aside-box-header">
        <h4>{{ __($config['name']) }}</h4>
    </div>
    
    <div class="aside-box-content">
        <div class="img-maps popup-info">
            @if (! is_null(get_info_bencana_post()))
            <a href="{{ get_info_bencana_post()->image }}" data-sub-html="<h4>{{ get_info_bencana_post()->title }}</h4><p>{{ get_info_bencana_post()->description }}.</p>" >
                <img src="{{ get_info_bencana_post()->image }}" class="img-responsive" style="width:100%;"/>
            </a>
            @else
            <a href="/uploads/24/2018-01-03-infografis-dampak-bencana-1-jan-19-des-2017.jpg" data-sub-html="<h4> Infografis Info Bencana </h4><p> Bencana Tahun 2017.</p>" >
                <img src="/uploads/24/2018-01-03-infografis-dampak-bencana-1-jan-19-des-2017.jpg" class="img-responsive" style="width:100%;">
            </a>
            @endif
        </div>
        <br>
        <div class="pull-right">
            <strong>
                <a href="{{ url('/publikasi/rekapitulasi-bencana') }}">{{ __('tabs.selengkapnya') }}>></a>
            </strong>
        </div>
        <br><br>
    </div>
</div>