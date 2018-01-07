@if (!empty($url))
    <button class="btn btn-success btn-lg mrb50 embed view-pdf" data-iframe="true" data-src="{{ $url }}"> Lihat Dokumen </button>
@else
    <p>{{ __('PDF URL is invalid.') }}</p>
@endif