@if (!empty($url))
    <button class="btn btn-primary btn-primary mrb50 embed view-pdf" data-iframe="true" data-src="{{ $url }}"> Lihat Dokumen <i class="fa fa-file-pdf-o"></i></button>
@else
    <p>{{ __('PDF URL is invalid.') }}</p>
@endif