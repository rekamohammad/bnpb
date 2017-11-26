{!! Form::hidden('gallery', $value ? json_encode($value) : null, ['id' => 'gallery-data', 'class' => 'form-control']) !!}
<div>
    <div class="list-photos-gallery">
        <div class="row">
            @if (!empty($value))
                @foreach ($value as $key => $item)
                    <div class="col-md-2 col-sm-3 col-xs-4 photo-gallery-item" data-id="{{ $key }}">
                        <div class="gallery_image_wrapper">
                            <img src="{{ get_image_url(array_get($item, 'img'), 'thumb') }}" alt="{{ trans('gallery::gallery.item') }}">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <a class="btn_select_gallery">{{ trans('gallery::gallery.select_image') }}</a>
        <a href="#" class="text-danger reset-gallery @if (empty($value)) hidden @endif">{{ trans('gallery::gallery.reset') }}</a>
    </div>
</div>

<div id="edit-gallery-item" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('gallery::gallery.update_photo_description') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p><input type="text" class="form-control" id="gallery-item-description" placeholder="{{ trans('gallery::gallery.update_photo_description_placeholder') }}"></p>
            </div>

            <div class="modal-footer">
                <button class="pull-left btn btn-danger" id="delete-gallery-item" href="#">{{ trans('gallery::gallery.delete_photo') }}</button>
                <button class="pull-right btn btn-default" data-dismiss="modal">{{ trans('bases::forms.cancel') }}</button>
                <button class="pull-right btn btn-primary" id="update-gallery-item">{{ trans('bases::forms.update') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->

@push('footer')
<script>
    $(document).ready(function () {

        $('.btn_select_gallery').rvMedia({
            onSelectFiles: function (files, $el) {
                var result = $('#gallery-data');
                var images = [];
                var last_index = 0;
                if (result.val() != '') {
                    images = $.parseJSON(result.val());
                    last_index = $('.list-photos-gallery .photo-gallery-item:last-child').data('id') + 1;
                }
                $.each(files, function (index, file) {
                    images.push({'img': file.url, 'description': null});
                    $('.list-photos-gallery .row').append('<div class="col-md-2 col-sm-3 col-xs-4 photo-gallery-item" data-id="' + (last_index + index) + '"><div class="gallery_image_wrapper"><img src="' + file.thumb + '" /></div></div>');
                })
                result.val(JSON.stringify(images));
                $('.reset-gallery').removeClass('hidden');
            }
        });

        var gallery_field = $('#gallery-data');
        var list_photo_gallery = $('.list-photos-gallery');
        var edit_gallery_modal = $('#edit-gallery-item');

        $('.reset-gallery').on('click', function (event) {
            event.preventDefault();
            $('.list-photos-gallery .photo-gallery-item').remove();
            gallery_field.val('');
            $(this).addClass('hidden');
        });

        list_photo_gallery.on('click', '.photo-gallery-item', function () {
            var id = $(this).data('id');
            $('#delete-gallery-item').data('id', id);
            $('#update-gallery-item').data('id', id);
            var images = $.parseJSON($('#gallery-data').val());
            if (images != null && typeof images[id] != 'undefined') {
                $('#gallery-item-description').val(images[id].description);
            }
            edit_gallery_modal.modal('show');
        });

        edit_gallery_modal.on('click', '#delete-gallery-item', function (event) {
            event.preventDefault();
            edit_gallery_modal.modal('hide');
            var id = $(this).data('id');
            var parent = list_photo_gallery.find('.photo-gallery-item[data-id=' + $(this).data('id') + ']');
            var images = $.parseJSON(gallery_field.val());
            var newListImages = [];
            $.each(images, function (index, el) {
                if (index != id) {
                    newListImages.push(el);
                }
            });
            gallery_field.val(JSON.stringify(newListImages));
            parent.remove();
            if (list_photo_gallery.find('.photo-gallery-item').length == 0) {
                $('.reset-gallery').addClass('hidden');
            }
        });

        edit_gallery_modal.on('click', '#update-gallery-item', function (event) {
            event.preventDefault();
            var id = $(this).data('id');
            var result = $('#gallery-data');
            edit_gallery_modal.modal('hide');
            var images = $.parseJSON(result.val());
            if (images != null && typeof images[id] != 'undefined') {
                images[id].description = $('#gallery-item-description').val();
            }
            result.val(JSON.stringify(images));

        });
    });
</script>
@endpush