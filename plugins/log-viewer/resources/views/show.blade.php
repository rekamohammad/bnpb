@extends('bases::layouts.master')

@section('content')
    @include('log-viewer::partials.style')
    <div class="main-form">
    <div class="row">
        <div class="col-md-2">
            @include('log-viewer::partials.menu')
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('log-viewer::log-viewer.log_info') }} :
                    <div class="group-btns pull-right">
                        <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-success">
                            <i class="fa fa-download"></i> {{ trans('log-viewer::log-viewer.download') }}
                        </a>
                        <a href="#delete-log-modal" class="btn btn-danger" data-toggle="modal">
                            <i class="fa fa-trash-o"></i> {{ trans('log-viewer::log-viewer.delete') }}
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <td>{{ trans('log-viewer::log-viewer.file_path') }} :</td>
                            <td colspan="5">{{ $log->getPath() }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ trans('log-viewer::log-viewer.log_entries') }} :</td>
                            <td>
                                <span class="label label-primary">{{ $entries->total() }}</span>
                            </td>
                            <td>{{ trans('log-viewer::log-viewer.size') }} :</td>
                            <td>
                                <span class="label label-primary">{{ $log->size() }}</span>
                            </td>
                            <td>{{ trans('bases::tables.created_at') }} :</td>
                            <td>
                                <span class="label label-primary">{{ $log->createdAt() }}</span>
                            </td>
                            <td>{{ trans('bases::tables.updated_at') }} :</td>
                            <td>
                                <span class="label label-primary">{{ $log->updatedAt() }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                @if ($entries->hasPages())
                    <div class="panel-heading">
                        {!! $entries->render() !!}
                        <span class="label label-info pull-right">
                            {{ trans('log-viewer::log-viewer.page') }} {!! $entries->currentPage() !!} {{ trans('log-viewer::log-viewer.of') }} {!! $entries->lastPage() !!}
                        </span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="entries" class="table table-condensed">
                        <thead>
                        <tr>
                            <th width="100">{{ trans('log-viewer::log-viewer.env') }}</th>
                            <th width="120">{{ trans('log-viewer::log-viewer.level') }}</th>
                            <th width="65">{{ trans('log-viewer::log-viewer.time') }}</th>
                            <th>{{ trans('log-viewer::log-viewer.header') }}</th>
                            <th class="text-center" width="100">{{ trans('log-viewer::log-viewer.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($entries as $key => $entry)
                            <tr>
                                <td>
                                    <span class="label label-env">{{ $entry->env }}</span>
                                </td>
                                <td>
                                    <span class="level level-{{ $entry->level }}">{!! $entry->level() !!}</span>
                                </td>
                                <td>
                                    <span class="label label-default">{{ $entry->datetime->format('H:i:s') }}</span>
                                </td>
                                <td>
                                    <p>{{ $entry->header }}</p>
                                </td>
                                <td class="text-right">
                                    @if ($entry->hasStack())
                                        <a class="btn btn-default" role="button" data-toggle="collapse"
                                           href="#log-stack-{{ $key }}" aria-expanded="false"
                                           aria-controls="log-stack-{{ $key }}">
                                            <i class="fa fa-toggle-on"></i> {{ trans('log-viewer::log-viewer.stack') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @if ($entry->hasStack())
                                <tr>
                                    <td colspan="5" class="stack">
                                        <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                            {!! preg_replace("/\n/", '<br>', $entry->stack) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($entries->hasPages())
                    <div class="panel-footer">
                        {!! $entries->render() !!}
                        <span class="label label-info pull-right">
                            Page {!! $entries->currentPage() !!} of {!! $entries->lastPage() !!}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

        <div id="delete-log-modal" class="modal fade">
            <div class="modal-dialog">
                <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="post">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="date" value="{{ $log->date }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{{ trans('log-viewer::log-viewer.delete_log_file') }}</h4>
                        </div>
                        <div class="modal-body">
                            <p>{!! trans('log-viewer::log-viewer.confirm_delete_msg', ['date' => $log->date]) !!}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-default pull-left" data-dismiss="modal">{{ trans('bases::forms.cancel') }}</button>
                            <button type="submit" class="btn btn-sm btn-danger" data-loading-text="{{ trans('log-viewer::log-viewer.loading') }}">{{ trans('log-viewer::log-viewer.delete_button') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection
@section('javascript')
    <script>
        $(function () {
            var deleteLogModal = $('div#delete-log-modal');
            var deleteLogForm = $('form#delete-log-form');
            var submitBtn = deleteLogForm.find('button[type=submit]');

            deleteLogForm.submit(function (event) {
                event.preventDefault();
                submitBtn.button('loading');

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        submitBtn.button('reset');
                        if (data.result === 'success') {
                            deleteLogModal.modal('hide');
                            location.replace('{{ route('log-viewer::logs.list') }}');
                        } else {
                            Botble.showNotice('error', 'OOPS ! This is a lack of coffee exception !', Botble.languages.notices_msg.error);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        Botble.showNotice('error', 'AJAX ERROR ! Check the console !', Botble.languages.notices_msg.error);
                        console.error(errorThrown);
                        submitBtn.button('reset');
                    }
                });
                return false;
            });
        });
    </script>
@endsection
