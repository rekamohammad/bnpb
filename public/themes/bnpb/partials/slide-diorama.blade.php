@php
    $_diorama = get_diorama_slide();
@endphp
@if ($_diorama)
<div id="main-diorama" class="main-diorama diorama-section">
    <div class="banner-slider-wrap">
        @foreach ($_diorama as $feature_item)
            <div class="slide slide-item caption-slide">
                <img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                    style="background-image: url('{{ get_object_image($feature_item->image) }}');">
                <div class="slide-caption">{{ $feature_item->description }}</div>
            </div>
        @endforeach
    </div>
</div>
@endif

