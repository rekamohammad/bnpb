<div class="aside-box">
    <div class="aside-box-content">
        <div class="img-maps">
            <div class="banner-wrapper theme-default">
                <div id="banner" class="nivoSlider">
                    @if (count(get_List_Banner()) != 0)
                        @foreach(get_List_Banner() as $data)
                            <a href="{{ $data->url }}">
                                @if($data->type == 'file')
                                    <img src="{{ url('/uploads/banner/'.$data->filename) }}" data-thumb="{{ url('/uploads/banner/'.$data->filename) }}" alt="" data-transition="slideInLeft" class="img-responsive" style="width:100%;"/>
                                @else
                                    <img src="{{ $data->filename }}" data-thumb="{{ $data->filename }}" alt="" data-transition="slideInLeft" class="img-responsive" style="width:100%;"/>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div
    </div>
</div>