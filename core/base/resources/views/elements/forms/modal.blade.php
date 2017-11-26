<div id="{{ $name }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-{{ $type }}">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ $title }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{!! $content !!}</p>
            </div>

            <div class="modal-footer">
                <a class="pull-left btn btn-{{ $type }}" id="{{ $action_id }}" href="#">{{ $action_name }}</a>
                <button class="pull-right btn btn-primary" data-dismiss="modal">{{ trans('bases::tables.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->