<div class="image-box">
    <input type="hidden"
           name="{{ $name }}"
           value="{{ $value }}"
           class="image-data">
    <img src="{{ get_object_image($value, 'thumb') }}"
        alt="preview image" class="preview_image">
    <div class="image-box-actions">
        <a class="btn_gallery" data-result="{{ $name }}" data-action="{{ $attributes['action'] or 'select-image' }}">
            {{ trans('bases::forms.choose_image') }}
        </a> |
        <a class="btn_remove_image">
            {{ trans('bases::forms.remove_image') }}
        </a>
    </div>
</div>
