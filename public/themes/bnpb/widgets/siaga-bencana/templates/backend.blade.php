<div class="form-group">
    <label for="widget-name">{{ __('Name') }}</label>
    <input type="text" class="form-control" name="name" value="{{ $config['name'] }}">
</div>
<div class="form-group">
    <label for="number_display">{{ __('Content') }}</label>
    <textarea name="content" class="form-control" rows="7">{{ $config['content'] }}</textarea>
</div>
