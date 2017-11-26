<tr class="@if (!empty($odd) && $odd == true) odd @else even @endif">
    <td>{{ $data['name'] }}</td>
    <td>{{ $data['description'] }}</td>
    <td>{{ $data['date'] }}</td>
    <td>
        <a href="{{ route('backups.download.database', $key) }}" class="text-success tip" title="{{ trans('backup::backup.download_database') }}"><i class="icon icon-database"></i></a>
        <a href="{{ route('backups.download.uploads.folder', $key) }}" class="text-primary tip" title="{{ trans('backup::backup.download_uploads_folder') }}"><i class="icon icon-download"></i></a>
        <a data-section="{{ route('backups.delete', $key) }}" class="text-danger deleteDialog tip" title="{{ trans('bases::tables.delete_entry') }}"><i class="icon icon-trash"></i></a>
        <a data-section="{{ route('backups.restore', $key) }}" class="text-info restoreBackup tip" title="{{ trans('backup::backup.restore_tooltip') }}"><i class="icon icon-publish"></i></a>
    </td>
</tr>