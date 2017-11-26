<div id="edit-slug-box" @if (empty($value) && !$errors->has($name)) class="hidden" @endif>
    <label class="control-label required" for="current-slug">{{ trans('bases::forms.permalink') }}:</label>
    <span id="sample-permalink">
        <a class="permalink" target="_blank" href="{{ str_replace('--slug--', $value, $preview) }}">
            <span class="default-slug">{{ url($default_slug) }}/<span id="editable-post-name">{{ $value }}</span>.html</span>
        </a>
    </span>
    ‎<span id="edit-slug-buttons">
        <button type="button" class="btn btn-default" id="change_slug">{{ trans('bases::forms.edit') }}</button>
        <button type="button" class="save btn btn-default" id="btn-ok">{{ trans('bases::forms.ok') }}</button>
        <button type="button" class="cancel button-link">{{ trans('bases::forms.cancel') }}</button>
    </span>
</div>
<input type="hidden" id="current-slug" name="{{ $name }}" value="{{ $value }}">
<div data-url="{{ $url }}" data-view="{{ str_replace('--slug--', $value, $preview) }}" id="object_id" data-id="{{ $id }}"></div>