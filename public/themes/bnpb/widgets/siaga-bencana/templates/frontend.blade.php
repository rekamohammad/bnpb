<div class="aside-box">
    <div class="aside-box-header">
        <h4>{{ __($config['name']) }}</h4>
    </div>

    <div class="aside-box-content">
        <div class="img-maps popup-info">
            {!! $config['content'] !!}
        </div>
        <br>
        <div class="pull-right">
            <strong>
                <a href="{{ url('/publikasi/siaga-bencana') }}">{{ __('tabs.selengkapnya') }}>></a>
            </strong>
        </div>
        <br>
    </div> 
</div>