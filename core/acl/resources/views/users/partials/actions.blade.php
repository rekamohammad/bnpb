<a href="{{ route('user.profile.view', $item->id) }}" class="btn btn-icon btn-primary tip" data-original-title="View user's profile"><i class="fa fa-eye"></i></a>

<a class="btn btn-icon btn-danger deleteDialog tip" data-toggle="modal" data-section="{{ route('users.delete', $item->id) }}" role="button" data-original-title="{{ trans('bases::tables.delete_entry') }}" >
    <i class="fa fa-trash-o"></i>
</a>