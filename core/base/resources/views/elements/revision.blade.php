<div class="form-group" style="min-height: 400px;">
    @if ($model->revisionHistory !== null && count($model->revisionHistory)>0)
        <table class="table table-bordered table-striped" id="table">
            <thead>
            <tr>
                <th>{{ trans('bases::tables.author') }}</th>
                <th>{{ trans('bases::tables.column') }}</th>
                <th>{{ trans('bases::tables.origin') }}</th>
                <th>{{ trans('bases::tables.after_change') }}</th>
                <th>{{ trans('bases::tables.created_at') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($model->revisionHistory as $history)
                <tr>
                    
                    <td style="min-width: 145px;">@if ($history->userResponsible()) {{ $history->userResponsible()->getFullName() }} @endif</td>
                    
                    <td style="min-width: 145px;">{{ $history->fieldName() }}</td>
                    <td>{{ $history->oldValue() }}</td>
                    <td>{{ $history->newValue() }}</td>
                    <td style="min-width: 145px;">{{ date_from_database($history->created_at, config('cms.date_format.date_time')) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
