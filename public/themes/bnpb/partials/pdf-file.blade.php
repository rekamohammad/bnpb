@if (!empty($url))
    <div class="embed-responsive embed-responsive-16by9 mb30">
        <embed src="{{ $url }}" width="500" height="375" type='application/pdf'>
        <iframe class="embed-responsive-item" allowfullscreen frameborder="0" height="500" src=""></iframe>
    </div>
@else
    <p>{{ __('PDF URL is invalid.') }}</p>
@endif