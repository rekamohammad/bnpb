@if (!empty($url))
	<video controls="" autoplay="" name="media">
		<source src="{{ $url }}" type="audio/mpeg">
	</video>
@else
    <p>{{ __('Audio URL is invalid.') }}</p>
@endif