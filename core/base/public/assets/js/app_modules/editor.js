function setImageValue(file) {
    $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
}

var BEditor = {
    initEditor: function (element, extraConfig) {
        if (element.length) {
            if ($('.editor-ckeditor').length > 0) {
                var config = {
                    filebrowserImageBrowseUrl: Botble.routes.media + '?media-action=select-files&method=ckeditor&type=image',
                    filebrowserImageUploadUrl: Botble.routes.media_upload_from_editor + '?method=ckeditor&type=image&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    filebrowserWindowWidth: '768',
                    filebrowserWindowHeight: '500',
                    height: 356,
                    allowedContent: true
                };
                var mergeConfig = {};
                $.extend(mergeConfig, config, extraConfig);
                CKEDITOR.replace(element.prop('id'), mergeConfig);
            }

            if ($('.editor-tinymce').length > 0) {

                tinymce.init({
                    menubar: false,
                    selector:'#' + element.prop('id'),
                    skin: 'voyager',
                    min_height: 600,
                    resize: 'vertical',
                    plugins: 'link, image, code, youtube, giphy, table, textcolor',
                    extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
                    file_browser_callback: function(field_name, url, type, win) {
                        if (type === 'image') {
                            $('#upload_file').trigger('click');
                        }
                    },
                    toolbar: 'styleselect bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table youtube giphy | code',
                    convert_urls: false,
                    image_caption: true,
                    image_title: true
                });
            }
        }
    }
};

$(document).ready(function () {
    if ($('.editor-ckeditor').length > 0) {
        BEditor.initEditor($('.editor-ckeditor'), {});
    }
    if ($('.editor-tinymce').length > 0) {
        BEditor.initEditor($('.editor-tinymce'), {});
    }
});
//# sourceMappingURL=editor.js.map
