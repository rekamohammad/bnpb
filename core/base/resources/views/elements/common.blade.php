<script type="text/javascript">

    var Botble = Botble || {};

    Botble.variables = {
        youtube_api_key: '{{ env('YOUTUBE_DATA_API_KEY') }}'
    };

    Botble.routes = {
        home: '{{ url('/') }}',
        admin: '{{ route('dashboard.index') }}',
        media: '{{ route('media.popup') }}',
        media_upload_from_editor: '{{ route('media.files.upload.from.editor') }}',
        change_plugin_status: '{{ route('plugins.change.status') }}'
    };

    Botble.languages = {
        'tables': {!! json_encode(trans('bases::tables'), JSON_HEX_APOS) !!},
        'notices_msg': {!! json_encode(trans('bases::notices'), JSON_HEX_APOS) !!},
        'pagination': {!! json_encode(trans('pagination'), JSON_HEX_APOS) !!},
        'system': {
            'character_remain': '{{ trans('bases::forms.character_remain') }}'
        }
    };

</script>

@if (session()->has('success_msg') || session()->has('error_msg') || isset($errors) || isset($error_msg))
    <script type="text/javascript">
        $(document).ready(function () {

            @if (session()->has('success_msg'))
                Botble.showNotice('success', '{{ session('success_msg') }}', Botble.languages.notices_msg.success);
            @endif
            @if (session()->has('error_msg'))
                Botble.showNotice('error', '{{ session('error_msg') }}', Botble.languages.notices_msg.error);
            @endif
            @if (isset($error_msg))
                Botble.showNotice('error', '{{ $error_msg }}', Botble.languages.notices_msg.error);
            @endif
            @if (isset($errors))
                @foreach ($errors->all() as $error)
                   Botble.showNotice('error', '{{ $error }}', Botble.languages.notices_msg.error);
                @endforeach
            @endif
        });
    </script>
@endif

{!! Form::modalAction('delete-crud-modal', trans('bases::tables.confirm_delete'), 'danger',  trans('bases::tables.confirm_delete_msg'), 'delete-crud-entry', trans('bases::tables.delete')) !!}
{!! Form::modalAction('delete-many-modal', trans('bases::tables.confirm_delete'), 'danger',  trans('bases::tables.confirm_delete_msg'), 'delete-many-entry', trans('bases::tables.delete')) !!}
