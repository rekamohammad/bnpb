var LaravelElixirBundle = (function (exports) {
'use strict';

var MediaConfig = $.parseJSON(localStorage.getItem('MediaConfig')) || {};

var defaultConfig = {
    app_key: '483a0xyzytz1242c0d520426e8ba366c530c3d9dxxx',
    request_params: {
        view_type: 'tiles',
        filter: 'everything',
        view_in: 'my_media',
        search: '',
        sort_by: 'created_at-desc',
        folder_id: 0,
    },
    hide_details_pane: false,
    icons: {
        folder: 'fa fa-folder-o',
    },
    actions_list: {
        basic: [
            {
                icon: 'fa fa-eye',
                name: 'Preview',
                action: 'preview',
                order: 0,
                class: 'rv-action-preview',
            } ],
        file: [
            {
                icon: 'fa fa-link',
                name: 'Copy link',
                action: 'copy_link',
                order: 0,
                class: 'rv-action-copy-link',
            },
            {
                icon: 'fa fa-pencil',
                name: 'Rename',
                action: 'rename',
                order: 1,
                class: 'rv-action-rename',
            },
            {
                icon: 'fa fa-copy',
                name: 'Make a copy',
                action: 'make_copy',
                order: 2,
                class: 'rv-action-make-copy',
            },
            {
                icon: 'fa fa-dot-circle-o',
                name: 'Set focus point',
                action: 'set_focus_point',
                order: 3,
                class: 'rv-action-set-focus-point',
            } ],
        user: [
            {
                icon: 'fa fa-share-alt',
                name: 'Share',
                action: 'share',
                order: 0,
                class: 'rv-action-share',
            },
            {
                icon: 'fa fa-ban',
                name: 'Remove share',
                action: 'remove_share',
                order: 1,
                class: 'rv-action-remove-share',
            },
            {
                icon: 'fa fa-star',
                name: 'Favorite',
                action: 'favorite',
                order: 2,
                class: 'rv-action-favorite',
            },
            {
                icon: 'fa fa-star-o',
                name: 'Remove favorite',
                action: 'remove_favorite',
                order: 3,
                class: 'rv-action-favorite',
            } ],
        other: [
            {
                icon: 'fa fa-download',
                name: 'Download',
                action: 'download',
                order: 0,
                class: 'rv-action-download',
            },
            {
                icon: 'fa fa-trash',
                name: 'Move to trash',
                action: 'trash',
                order: 1,
                class: 'rv-action-trash',
            },
            {
                icon: 'fa fa-eraser',
                name: 'Delete permanently',
                action: 'delete',
                order: 2,
                class: 'rv-action-delete',
            },
            {
                icon: 'fa fa-undo',
                name: 'Restore',
                action: 'restore',
                order: 3,
                class: 'rv-action-restore',
            } ],
    },
    denied_download: [
        'youtube' ],
};

if (!MediaConfig.app_key || MediaConfig.app_key !== defaultConfig.app_key) {
    MediaConfig = defaultConfig;
}

var RecentItems = $.parseJSON(localStorage.getItem('RecentItems')) || [];

var Helpers = function Helpers () {};

Helpers.getUrlParam = function getUrlParam (paramName, url) {
        if ( url === void 0 ) url = null;

    if (!url) {
        url = window.location.search;
    }
    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    var match = url.match(reParam);
    return ( match && match.length > 1 ) ? match[1] : null;
};

Helpers.asset = function asset (url) {
    if (url.substring(0, 2) === '//' || url.substring(0, 7) === 'http://' || url.substring(0, 8) === 'https://') {
        return url;
    }

    var baseUrl = RV_MEDIA_URL.base_url.substr(-1, 1) !== '/' ? RV_MEDIA_URL.base_url + '/' : RV_MEDIA_URL.base_url;

    if (url.substring(0, 1) === '/') {
        return baseUrl + url.substring(1);
    }
    return baseUrl + url;
};

Helpers.showAjaxLoading = function showAjaxLoading ($element) {
        if ( $element === void 0 ) $element = $('.rv-media-main');

    $element
        .addClass('on-loading')
        .append($('#rv_media_loading').html());
};

Helpers.hideAjaxLoading = function hideAjaxLoading ($element) {
        if ( $element === void 0 ) $element = $('.rv-media-main');

    $element
        .removeClass('on-loading')
        .find('.loading-wrapper').remove();
};

Helpers.isOnAjaxLoading = function isOnAjaxLoading ($element) {
        if ( $element === void 0 ) $element = $('.rv-media-items');

    return $element.hasClass('on-loading');
};

Helpers.jsonEncode = function jsonEncode (object) {
    "use strict";
    if (typeof object === 'undefined') {
        object = null;
    }
    return JSON.stringify(object);
};

Helpers.jsonDecode = function jsonDecode (jsonString, defaultValue) {
    "use strict";
    if (!jsonString) {
        return defaultValue;
    }
    if (typeof jsonString === 'string') {
        var result;
        try {
            result = $.parseJSON(jsonString);
        } catch (err) {
            result = defaultValue;
        }
        return result;
    }
    return jsonString;
};

Helpers.getRequestParams = function getRequestParams () {
    if (window.rvMedia.options && window.rvMedia.options.open_in === 'modal') {
        return $.extend(true, MediaConfig.request_params, window.rvMedia.options || {});
    }
    return MediaConfig.request_params;
};

Helpers.getConfigs = function getConfigs () {
    return MediaConfig;
};

Helpers.storeConfig = function storeConfig () {
    localStorage.setItem('MediaConfig', Helpers.jsonEncode(MediaConfig));
};

Helpers.storeRecentItems = function storeRecentItems () {
    localStorage.setItem('RecentItems', Helpers.jsonEncode(RecentItems));
};

Helpers.addToRecent = function addToRecent (id) {
    if (id instanceof Array) {
        _.each(id, function (value) {
            RecentItems.push(value);
        });
    } else {
        RecentItems.push(id);
    }
};

Helpers.getItems = function getItems () {
    var items = [];
    $('.js-media-list-title').each(function () {
        var $box = $(this);
        var data = $box.data() || {};
        data.index_key = $box.index();
        items.push(data);
    });
    return items;
};

Helpers.getSelectedItems = function getSelectedItems () {
    var selected = [];
    $('.js-media-list-title input[type=checkbox]:checked').each(function () {
        var $box = $(this).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
    });
    return selected;
};

Helpers.getSelectedFiles = function getSelectedFiles () {
    var selected = [];
    $('.js-media-list-title[data-context=file] input[type=checkbox]:checked').each(function () {
        var $box = $(this).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
    });
    return selected;
};

Helpers.getSelectedFolder = function getSelectedFolder () {
    var selected = [];
    $('.js-media-list-title[data-context=folder] input[type=checkbox]:checked').each(function () {
        var $box = $(this).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
    });
    return selected;
};

Helpers.isUseInModal = function isUseInModal () {
    return Helpers.getUrlParam('media-action') === 'select-files' || (window.rvMedia && window.rvMedia.options && window.rvMedia.options.open_in === 'modal');
};

Helpers.resetPagination = function resetPagination () {
    RV_MEDIA_CONFIG.pagination = { paged: 1, posts_per_page: 40, in_process_get_media: false, has_more: true};
};

var EditorService = function EditorService () {};

EditorService.editorSelectFile = function editorSelectFile (selectedFiles) {

    var is_ckeditor = Helpers.getUrlParam('CKEditor') || Helpers.getUrlParam('CKEditorFuncNum');

    if (window.opener && is_ckeditor) {
        var firstItem = _.first(selectedFiles);

        window.opener.CKEDITOR.tools.callFunction(Helpers.getUrlParam('CKEditorFuncNum'), firstItem.url);

        if (window.opener) {
            window.close();
        }
    } else {
        // No WYSIWYG editor found, use custom method.
    }
};

var rvMedia = function rvMedia(selector, options) {
    window.rvMedia = window.rvMedia || {};

    var $body = $('body');

    var defaultOptions = {
        multiple: true,
        type: '*',
        onSelectFiles: function (files, $el) {

        }
    };

    options = $.extend(true, defaultOptions, options);

    var clickCallback = function (event) {
        event.preventDefault();
        var $current = $(this);
        $('#rv_media_modal').modal();

        window.rvMedia.options = options;
        window.rvMedia.options.open_in = 'modal';

        window.rvMedia.$el = $current;

        MediaConfig.request_params.filter = 'everything';
        Helpers.storeConfig();

        var ele_options = window.rvMedia.$el.data('rv-media');
        if (typeof ele_options !== 'undefined' && ele_options.length > 0) {
            ele_options = ele_options[0];
            window.rvMedia.options = $.extend(true, window.rvMedia.options, ele_options || {});
            if (typeof ele_options.selected_file_id !== 'undefined') {
                window.rvMedia.options.is_popup = true;
            } else if (typeof window.rvMedia.options.is_popup !== 'undefined') {
                window.rvMedia.options.is_popup = undefined;
            }
        }

        if ($('#rv_media_body .rv-media-container').length === 0) {
            $('#rv_media_body').load(RV_MEDIA_URL.popup, function (data) {
                if (data.error) {
                    alert(data.message);
                }
                $('#rv_media_body')
                    .removeClass('media-modal-loading')
                    .closest('.modal-content').removeClass('bb-loading');
            });
        } else {
            $(document).find('.rv-media-container .js-change-action[data-type=refresh]').trigger('click');
        }
    };

    if (typeof selector === 'string') {
        $body.on('click', selector, clickCallback);
    } else {
        selector.on('click', clickCallback);
    }
};

window.RvMediaStandAlone = rvMedia;

$('.js-insert-to-editor').off('click').on('click', function (event) {
    event.preventDefault();
    var selectedFiles = Helpers.getSelectedFiles();
    if (_.size(selectedFiles) > 0) {
        EditorService.editorSelectFile(selectedFiles);
    }
});

$.fn.rvMedia = function (options) {
    var $selector = $(this);

    MediaConfig.request_params.filter = 'everything';
    if (MediaConfig.request_params.view_in === 'trash') {
        $(document).find('.js-insert-to-editor').prop('disabled', true);
    } else {
        $(document).find('.js-insert-to-editor').prop('disabled', false);
    }
    Helpers.storeConfig();

    new rvMedia($selector, options);
};

exports.EditorService = EditorService;

return exports;

}({}));
//# sourceMappingURL=integrate.js.map
