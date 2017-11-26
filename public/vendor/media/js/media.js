(function () {
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

var MessageService = function MessageService () {};

MessageService.showMessage = function showMessage (type, message, messageHeader) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-bottom-right',
        onclick: null,
        showDuration: 1000,
        hideDuration: 1000,
        timeOut: 10000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };
    toastr[type](message, messageHeader);
};

MessageService.handleError = function handleError (data) {
    if (typeof (data.responseJSON) !== 'undefined') {
        if (typeof (data.responseJSON.message) !== 'undefined') {
            MessageService.showMessage('error', data.responseJSON.message, RV_MEDIA_CONFIG.translations.message.error_header);
        } else {
            $.each(data.responseJSON, function (index, el) {
                $.each(el, function (key, item) {
                    MessageService.showMessage('error', item, RV_MEDIA_CONFIG.translations.message.error_header);
                });
            });
        }
    } else {
        MessageService.showMessage('error', data.statusText, RV_MEDIA_CONFIG.translations.message.error_header);
    }
};

var ActionsService = function ActionsService () {};

ActionsService.handleDropdown = function handleDropdown () {
    var selected = _.size(Helpers.getSelectedItems());

    ActionsService.renderActions();

    if (selected > 0) {
        $('.rv-dropdown-actions').removeClass('disabled');
    } else {
        $('.rv-dropdown-actions').addClass('disabled');
    }
};

ActionsService.handlePreview = function handlePreview () {
    var selected = [];

    _.each(Helpers.getSelectedFiles(), function (value, index) {
        if (_.contains(['image', 'youtube', 'pdf', 'text', 'video'], value.type)) {
            selected.push({
                src: value.url
            });
            RecentItems.push(value.id);
        }
    });

    if (_.size(selected) > 0) {
        $.fancybox.open(selected);
        Helpers.storeRecentItems();
    } else {
        this.handleGlobalAction('download');
    }
};

ActionsService.handleCopyLink = function handleCopyLink () {
    var links = '';
    _.each(Helpers.getSelectedFiles(), function (value, index) {
        if (!_.isEmpty(links)) {
            links += '\n';
        }
        links += value.full_url;
    });
    var $clipboardTemp = $('.js-rv-clipboard-temp');
    $clipboardTemp.data('clipboard-text', links);
    new Clipboard('.js-rv-clipboard-temp', {
        text: function (trigger) {
            return links;
        }
    });
    MessageService.showMessage('success', RV_MEDIA_CONFIG.translations.clipboard.success, RV_MEDIA_CONFIG.translations.message.success_header);
    $clipboardTemp.trigger('click');
};

ActionsService.handleGlobalAction = function handleGlobalAction (type, callback) {
    var selected = [];
    _.each(Helpers.getSelectedItems(), function (value, index) {
        selected.push({
            is_folder: value.is_folder,
            id: value.id,
            full_url: value.full_url,
            focus: value.focus,
        });
    });

    switch (type) {
        case 'rename':
            $('#modal_rename_items').modal('show').find('form.rv-form').data('action', type);
            break;
        case 'copy_link':
            ActionsService.handleCopyLink();
            break;
        case 'preview':
            ActionsService.handlePreview();
            break;
        case 'set_focus_point':
            var modal = $('#modal_set_focus_point');
            if (selected[0].focus.length === 0) {
                modal.find('.helper-tool-data-attr').val('');
                modal.find('.helper-tool-css3-val').val('');
                modal.find('.helper-tool-reticle-css').val('');
                $('.reticle').removeAttr('style');
            } else {
                modal.find('.helper-tool-data-attr').val(selected[0].focus.data_attribute);
                modal.find('.helper-tool-css3-val').val(selected[0].focus.css_bg_position);
                modal.find('.helper-tool-reticle-css').val(selected[0].focus.retice_css);
                $('.reticle').prop('style', selected[0].focus.retice_css);
            }
            modal.modal('show').find('form.rv-form').data('action', type).data('image', selected[0].full_url);
            break;
        case 'trash':
            $('#modal_trash_items').modal('show').find('form.rv-form').data('action', type);
            break;
        case 'delete':
            $('#modal_delete_items').modal('show').find('form.rv-form').data('action', type);
            break;
        case 'share':
            $('#modal_share_items').modal('show').find('form.rv-form').data('action', type);
            break;
        case 'empty_trash':
            $('#modal_empty_trash').modal('show').find('form.rv-form').data('action', type);
            break;
        case 'download':
            var downloadLink = RV_MEDIA_URL.download;
            var count = 0;
            _.each(Helpers.getSelectedItems(), function (value, index) {
                if (!_.contains(Helpers.getConfigs().denied_download, value.mime_type)) {
                    downloadLink += (count === 0 ? '?' : '&') + 'selected[' + count + '][is_folder]=' + value.is_folder + '&selected[' + count + '][id]=' + value.id;
                    count++;
                }
            });
            if (downloadLink !== RV_MEDIA_URL.download) {
                window.open(downloadLink, '_blank');
            } else {
                MessageService.showMessage('error', RV_MEDIA_CONFIG.translations.download.error, RV_MEDIA_CONFIG.translations.message.error_header);
            }
            break;
        default:
            ActionsService.processAction({
                selected: selected,
                action: type
            }, callback);
            break;
    }
};

ActionsService.processAction = function processAction (data, callback) {
        if ( callback === void 0 ) callback = null;

    $.ajax({
        url: RV_MEDIA_URL.global_actions,
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function () {
            Helpers.showAjaxLoading();
        },
        success: function (res) {
            Helpers.resetPagination();
            if (!res.error) {
                MessageService.showMessage('success', res.message, RV_MEDIA_CONFIG.translations.message.success_header);
            } else {
                MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
            }
            if (callback) {
                callback(res);
            }
        },
        complete: function (data) {
            Helpers.hideAjaxLoading();
        },
        error: function (data) {
            MessageService.handleError(data);
        }
    });
};

ActionsService.renderRenameItems = function renderRenameItems () {
    var VIEW = $('#rv_media_rename_item').html();
    var $itemsWrapper = $('#modal_rename_items .rename-items').empty();

    _.each(Helpers.getSelectedItems(), function (value, index) {
        var item = VIEW
                .replace(/__icon__/gi, value.icon || 'fa fa-file-o')
                .replace(/__placeholder__/gi, 'Input file name')
                .replace(/__value__/gi, value.name);
        var $item = $(item);
        $item.data('id', value.id);
        $item.data('is_folder', value.is_folder);
        $item.data('name', value.name);
        $itemsWrapper.append($item);
    });
};

ActionsService.renderActions = function renderActions () {
    var hasFolderSelected = Helpers.getSelectedFolder().length > 0;

    var ACTION_TEMPLATE = $('#rv_action_item').html();
    var initialized_item = 0;
    var $dropdownActions = $('.rv-dropdown-actions .dropdown-menu');
    $dropdownActions.empty();

    var actionsList = $.extend({}, true, Helpers.getConfigs().actions_list);

    if (hasFolderSelected) {
        actionsList.basic = _.reject(actionsList.basic, function (item) {
            return item.action === 'preview';
        });
        actionsList.file = _.reject(actionsList.file, function (item) {
            return item.action === 'copy_link';
        });
        actionsList.file = _.reject(actionsList.file, function (item) {
            return item.action === 'set_focus_point';
        });

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.create')) {
            actionsList.file = _.reject(actionsList.file, function (item) {
                return item.action === 'make_copy';
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.edit')) {
            actionsList.file = _.reject(actionsList.file, function (item) {
                return _.contains(['rename'], item.action);
            });

            actionsList.user = _.reject(actionsList.user, function (item) {
                return _.contains(['rename', 'share', 'remove_share', 'un_share'], item.action);
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.trash')) {
            actionsList.other = _.reject(actionsList.other, function (item) {
                return _.contains(['trash', 'restore'], item.action);
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.delete')) {
            actionsList.other = _.reject(actionsList.other, function (item) {
                return _.contains(['delete'], item.action);
            });
        }
    }

    var selectedFiles = Helpers.getSelectedFiles();
    if (selectedFiles.length > 0 && selectedFiles[0].type !== 'image') {
        actionsList.file = _.reject(actionsList.file, function (item) {
            return item.action === 'set_focus_point';
        });
    }

    var can_preview = false;
    _.each(selectedFiles, function (value) {
        if (_.contains(['image', 'youtube', 'pdf', 'text', 'video'], value.type)) {
            can_preview = true;
        }
    });

    if (!can_preview) {
        actionsList.basic = _.reject(actionsList.basic, function (item) {
            return item.action === 'preview';
        });
    }

    if (RV_MEDIA_CONFIG.mode === 'simple') {
        actionsList.user = _.reject(actionsList.user, function (item) {
            return _.contains(['share', 'remove_share', 'un_share'], item.action);
        });
    }

    if (selectedFiles.length > 0) {
        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.create')) {
            actionsList.file = _.reject(actionsList.file, function (item) {
                return item.action === 'make_copy';
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.edit')) {
            actionsList.file = _.reject(actionsList.file, function (item) {
                return _.contains(['rename', 'set_focus_point'], item.action);
            });

            actionsList.user = _.reject(actionsList.user, function (item) {
                return _.contains(['share', 'remove_share', 'un_share'], item.action);
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.trash')) {
            actionsList.other = _.reject(actionsList.other, function (item) {
                return _.contains(['trash', 'restore'], item.action);
            });
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.delete')) {
            actionsList.other = _.reject(actionsList.other, function (item) {
                return _.contains(['delete'], item.action);
            });
        }
    }

    _.each(actionsList, function (action, key) {
        _.each(action, function (item, index) {
            var is_break = false;
            switch (Helpers.getRequestParams().view_in) {
                case 'my_media':
                    if (_.contains(['remove_favorite', 'delete', 'restore', 'remove_share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'shared':
                    if (_.contains(['remove_favorite', 'delete', 'restore', 'make_copy', 'remove_share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'shared_with_me':
                    if (_.contains(['remove_favorite', 'delete', 'restore', 'make_copy', 'share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'public':
                    if (_.contains(['remove_favorite', 'delete', 'restore', 'make_copy', 'share', 'remove_share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'recent':
                    if (_.contains(['remove_favorite', 'delete', 'restore', 'make_copy', 'remove_share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'favorites':
                    if (_.contains(['favorite', 'delete', 'restore', 'make_copy', 'remove_share'], item.action)) {
                        is_break = true;
                    }
                    break;
                case 'trash':
                    if (!_.contains(['preview', 'delete', 'restore', 'rename', 'download'], item.action)) {
                        is_break = true;
                    }
                    break;
            }
            if (!is_break) {
                var template = ACTION_TEMPLATE
                    .replace(/__action__/gi, item.action || '')
                    .replace(/__icon__/gi, item.icon || '')
                    .replace(/__name__/gi, RV_MEDIA_CONFIG.translations.actions_list[key][item.action] || item.name);
                if (!index && initialized_item) {
                    template = '<li role="separator" class="divider"></li>' + template;
                }
                $dropdownActions.append(template);
            }
        });
        if (action.length > 0) {
            initialized_item++;
        }
    });
};

var ContextMenuService = function ContextMenuService () {};

ContextMenuService.initContext = function initContext () {
    if (jQuery().contextMenu) {
        $.contextMenu({
            selector: '.js-context-menu[data-context="file"]',
            build: function ($element, event) {
                return {
                    items: ContextMenuService._fileContextMenu(),
                };
            },
        });

        $.contextMenu({
            selector: '.js-context-menu[data-context="folder"]',
            build: function ($element, event) {
                return {
                    items: ContextMenuService._folderContextMenu(),
                };
            },
        });
    }
};

ContextMenuService._fileContextMenu = function _fileContextMenu () {
    var items = {
        preview: {
            name: 'Preview',
            icon: function (opt, $itemElement, itemKey, item) {
                $itemElement.html('<i class="fa fa-eye" aria-hidden="true"></i> ' + item.name);

                return 'context-menu-icon-updated';
            },
            callback: function (key, opt) {
                ActionsService.handlePreview();
            }
        },
        set_focus_point: {
            name: "Set focus point",
            icon: function (opt, $itemElement, itemKey, item) {
                $itemElement.html('<i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' + item.name);

                return 'context-menu-icon-updated';
            },
            callback: function (key, opt) {
                $('.js-files-action[data-action="set_focus_point"]').trigger('click');
            }
        },
    };

    _.each(Helpers.getConfigs().actions_list, function (actionGroup, key) {
        _.each(actionGroup, function (value) {
            items[value.action] = {
                name: value.name,
                icon: function (opt, $itemElement, itemKey, item) {
                    $itemElement.html('<i class="' + value.icon + '" aria-hidden="true"></i> ' + (RV_MEDIA_CONFIG.translations.actions_list[key][value.action] || item.name));

                    return 'context-menu-icon-updated';
                },
                callback: function (key, opt) {
                    $('.js-files-action[data-action="' + value.action + '"]').trigger('click');
                }
            };
        });
    });

    var except = [];

    switch (Helpers.getRequestParams().view_in) {
        case 'my_media':
            except = ['remove_favorite', 'delete', 'restore', 'remove_share'];
            break;
        case 'public':
            except = ['remove_favorite', 'delete', 'restore', 'remove_share'];
            break;
        case 'shared':
            except = ['make_copy', 'remove_favorite', 'delete', 'restore', 'remove_share'];
            break;
        case 'shared_with_me':
            except = ['share', 'remove_favorite', 'delete', 'restore', 'make_copy'];
            break;
        case 'recent':
            except = ['remove_favorite', 'delete', 'restore', 'remove_share', 'make_copy'];
            break;
        case 'favorites':
            except = ['favorite', 'delete', 'restore', 'remove_share', 'make_copy'];
            break;
        case 'trash':
            items = {
                preview: items.preview,
                rename: items.rename,
                download: items.download,
                delete: items.delete,
                restore: items.restore,
            };
            break;
    }

    _.each(except, function (value) {
        items[value] = undefined;
    });

    if (Helpers.getSelectedItems().length > 1) {
        items.set_focus_point = undefined;
    }

    var hasFolderSelected = Helpers.getSelectedFolder().length > 0;

    if (hasFolderSelected) {
        items.preview = undefined;
        items.set_focus_point = undefined;
        items.copy_link = undefined;

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.create')) {
            items.make_copy = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.edit')) {
            items.rename = undefined;
            items.share = undefined;
            items.remove_share = undefined;
            items.un_share = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.trash')) {
            items.trash = undefined;
            items.restore = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'folders.delete')) {
            items.delete = undefined;
        }
    }

    var selectedFiles = Helpers.getSelectedFiles();
    if (selectedFiles.length > 0 && selectedFiles[0].type !== 'image') {
        items.set_focus_point = undefined;
    }

    if (selectedFiles.length > 0) {
        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.create')) {
            items.make_copy = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.edit')) {
            items.rename = undefined;
            items.set_focus_point = undefined;
            items.share = undefined;
            items.remove_share = undefined;
            items.un_share = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.trash')) {
            items.trash = undefined;
            items.restore = undefined;
        }

        if (!_.contains(RV_MEDIA_CONFIG.permissions, 'files.delete')) {
            items.delete = undefined;
        }
    }

    var can_preview = false;
    _.each(selectedFiles, function (value) {
        if (_.contains(['image', 'youtube', 'pdf', 'text', 'video'], value.type)) {
            can_preview = true;
        }
    });

    if (!can_preview) {
        items.preview = undefined;
    }

    if (RV_MEDIA_CONFIG.mode === 'simple') {
        items.share = undefined;
        items.un_share = undefined;
        items.remove_share = undefined;
    }

    return items;
};

ContextMenuService._folderContextMenu = function _folderContextMenu () {
    var items = ContextMenuService._fileContextMenu();

    items.preview = undefined;
    items.set_focus_point = undefined;
    items.copy_link = undefined;

    return items;
};

ContextMenuService.destroyContext = function destroyContext () {
    if (jQuery().contextMenu) {
        $.contextMenu('destroy');
    }
};

var MediaList = function MediaList() {
    this.group = {};
    this.group.list = $('#rv_media_items_list').html();
    this.group.tiles = $('#rv_media_items_tiles').html();

    this.item = {};
    this.item.list = $('#rv_media_items_list_element').html();
    this.item.tiles = $('#rv_media_items_tiles_element').html();

    this.$groupContainer = $('.rv-media-items');
};


MediaList.prototype.renderData = function renderData (data, reload, load_more_file) {
        if ( reload === void 0 ) reload = false;
        if ( load_more_file === void 0 ) load_more_file = false;

    var _self = this;
    var MediaConfig = Helpers.getConfigs();
    var template = _self.group[Helpers.getRequestParams().view_type];

    var view_in = Helpers.getRequestParams().view_in;

    if (!_.contains(['my_media', 'public', 'trash', 'favorites', 'shared', 'shared_with_me', 'recent'], view_in)) {
        view_in = 'my_media';
    }

    template = template
        .replace(/__noItemIcon__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].icon || '')
        .replace(/__noItemTitle__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].title || '')
        .replace(/__noItemMessage__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].message || '');

    var $result = $(template);
    var $itemsWrapper = $result.find('ul');

    if (load_more_file && this.$groupContainer.find('.rv-media-grid ul').length > 0) {
        $itemsWrapper = this.$groupContainer.find('.rv-media-grid ul');
    }

    if (_.size(data.folders) > 0 || _.size(data.files) > 0) {
        $('.rv-media-items').addClass('has-items');
    } else {
        $('.rv-media-items').removeClass('has-items');
    }

    _.forEach(data.folders, function (value, index) {
        var item = _self.item[Helpers.getRequestParams().view_type];
        item = item
            .replace(/__type__/gi, 'folder')
            .replace(/__id__/gi, value.id)
            .replace(/__name__/gi, value.name || '')
            .replace(/__size__/gi, '')
            .replace(/__date__/gi, value.created_at || '')
            .replace(/__thumb__/gi, '<i class="fa fa-folder-o"></i>');
        var $item = $(item);
        _.forEach(value, function (val, index) {
            $item.data(index, val);
        });
        $item.data('is_folder', true);
        $item.data('icon', MediaConfig.icons.folder);
        $itemsWrapper.append($item);
    });

    _.forEach(data.files, function (value) {
        var item = _self.item[Helpers.getRequestParams().view_type];
        item = item
            .replace(/__type__/gi, 'file')
            .replace(/__id__/gi, value.id)
            .replace(/__name__/gi, value.name || '')
            .replace(/__size__/gi, value.size || '')
            .replace(/__date__/gi, value.created_at || '');
        if (Helpers.getRequestParams().view_type === 'list') {
            item = item
                .replace(/__thumb__/gi, '<i class="' + value.icon + '"></i>');
        } else {
            switch (value.mime_type) {
                case 'youtube':
                    item = item
                        .replace(/__thumb__/gi, '<img src="' + value.options.thumb + '" alt="' + value.name + '">');
                    break;
                default:
                    item = item
                        .replace(/__thumb__/gi, value.thumb ? '<img src="' + value.thumb + '" alt="' + value.name + '">' : '<i class="' + value.icon + '"></i>');
                    break;
            }
        }
        var $item = $(item);
        $item.data('is_folder', false);
        _.forEach(value, function (val, index) {
            $item.data(index, val);
        });
        $itemsWrapper.append($item);
    });
    if (reload !== false) {
        _self.$groupContainer.empty();
    }

    if (load_more_file && this.$groupContainer.find('.rv-media-grid ul').length > 0) {

    } else {
        _self.$groupContainer.append($result);
    }
    _self.$groupContainer.find('.loading-wrapper').remove();
    ActionsService.handleDropdown();

    //trigger event click for file selected
    $('.js-media-list-title[data-id=' + data.selected_file_id + ']').trigger('click');
};

var MediaDetails = function MediaDetails() {
    this.$detailsWrapper = $('.rv-media-main .rv-media-details');

    this.descriptionItemTemplate = '<div class="rv-media-name"><p>__title__</p>__url__</div>';

    this.onlyFields = [
        'name',
        'full_url',
        'size',
        'mime_type',
        'created_at',
        'updated_at',
        'nothing_selected' ];

    this.externalTypes = [
        'youtube',
        'vimeo',
        'metacafe',
        'dailymotion',
        'vine',
        'instagram' ];
};

MediaDetails.prototype.renderData = function renderData (data) {
    var _self = this;
    var thumb = data.type === 'image' ? '<img src="' + data.full_url + '" alt="' + data.name + '">' : data.mime_type === 'youtube' ? '<img src="' + data.options.thumb + '" alt="' + data.name + '">' : '<i class="' + data.icon + '"></i>';
    var description = '';
    var useClipboard = false;
    _.forEach(data, function (val, index) {
        if (_.contains(_self.onlyFields, index)) {
            if ((!_.contains(_self.externalTypes, data.type)) || (_.contains(_self.externalTypes, data.type) && !_.contains(['size', 'mime_type'], index))) {
                description += _self.descriptionItemTemplate
                    .replace(/__title__/gi, RV_MEDIA_CONFIG.translations[index])
                    .replace(/__url__/gi, val ? index === 'full_url' ? '<div class="input-group"><input id="file_details_url" type="text" value="' + val + '" class="form-control"><span class="input-group-btn"><button class="btn btn-default js-btn-copy-to-clipboard" type="button" data-clipboard-target="#file_details_url" title="Copied" data-trigger="click"><img class="clippy" src="' + Helpers.asset('/vendor/media/images/clippy.svg') + '" width="13" alt="Copy to clipboard"></button></span></div>' : '<span title="' + val + '">' + val + '</span>' : '');
                if (index === 'full_url') {
                    useClipboard = true;
                }
            }
        }
    });
    _self.$detailsWrapper.find('.rv-media-thumbnail').html(thumb);
    _self.$detailsWrapper.find('.rv-media-description').html(description);
    if (useClipboard) {
        var clipboard = new Clipboard('.js-btn-copy-to-clipboard');
        $('.js-btn-copy-to-clipboard').tooltip().on('mouseleave', function (event) {
            $(this).tooltip('hide');
        });
    }
};

var MediaService = function MediaService() {
    this.MediaList = new MediaList();
    this.MediaDetails = new MediaDetails();
    this.breadcrumbTemplate = $('#rv_media_breadcrumb_item').html();
};

MediaService.prototype.getMedia = function getMedia (reload, is_popup, load_more_file) {
        if ( reload === void 0 ) reload = false;
        if ( is_popup === void 0 ) is_popup = false;
        if ( load_more_file === void 0 ) load_more_file = false;

    if(typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
        if (RV_MEDIA_CONFIG.pagination.in_process_get_media) {
            return;
        } else {
            RV_MEDIA_CONFIG.pagination.in_process_get_media = true;
        }
    }

    var _self = this;

    _self.getFileDetails({
        icon: 'fa fa-picture-o',
        nothing_selected: '',
    });

    var params = Helpers.getRequestParams();

    if (params.view_in === 'recent') {
        params.recent_items = RecentItems;
    }

    if (is_popup === true) {
        params.is_popup = true;
    } else{
        params.is_popup = undefined;
    }

    params.onSelectFiles = undefined;

    if (typeof params.search != 'undefined' && params.search != '' && typeof params.selected_file_id != 'undefined') {
        params.selected_file_id = undefined;
    }

    params.load_more_file = load_more_file;
    if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
        params.paged = RV_MEDIA_CONFIG.pagination.paged;
        params.posts_per_page = RV_MEDIA_CONFIG.pagination.posts_per_page;
    }
    $.ajax({
        url: RV_MEDIA_URL.get_media,
        type: 'GET',
        data: params,
        dataType: 'json',
        beforeSend: function () {
            Helpers.showAjaxLoading();
        },
        success: function (res) {
            _self.MediaList.renderData(res.data, reload, load_more_file);
            _self.fetchQuota();
            _self.renderBreadcrumbs(res.data.breadcrumbs);
            MediaService.refreshFilter();
            ActionsService.renderActions();

            if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
                if (typeof RV_MEDIA_CONFIG.pagination.paged != 'undefined') {
                    RV_MEDIA_CONFIG.pagination.paged += 1;
                }

                if (typeof RV_MEDIA_CONFIG.pagination.in_process_get_media != 'undefined') {
                    RV_MEDIA_CONFIG.pagination.in_process_get_media = false;
                }

                if (typeof RV_MEDIA_CONFIG.pagination.posts_per_page != 'undefined' && res.data.files.length < RV_MEDIA_CONFIG.pagination.posts_per_page && typeof RV_MEDIA_CONFIG.pagination.has_more != 'undefined') {
                    RV_MEDIA_CONFIG.pagination.has_more = false;
                }
            }
        },
        complete: function (data) {
            Helpers.hideAjaxLoading();
        },
        error: function (data) {
            MessageService.handleError(data);
        }
    });
};

MediaService.prototype.getFileDetails = function getFileDetails (data) {
    this.MediaDetails.renderData(data);
};

MediaService.prototype.fetchQuota = function fetchQuota () {
    $.ajax({
        url: RV_MEDIA_URL.get_quota,
        type: 'GET',
        dataType: 'json',
        beforeSend: function () {

        },
        success: function (res) {
            var data = res.data;

            $('.rv-media-aside-bottom .used-analytics span').html(data.used + ' / ' + data.quota);
            $('.rv-media-aside-bottom .progress-bar').css({
                width: data.percent + '%',
            });
        },
        error: function (data) {
            MessageService.handleError(data);
        }
    });
};

MediaService.prototype.renderBreadcrumbs = function renderBreadcrumbs (breadcrumbItems) {
    var _self = this;
    var $breadcrumbContainer = $('.rv-media-breadcrumb .breadcrumb');
    $breadcrumbContainer.find('li').remove();

    _.each(breadcrumbItems, function (value, index) {
        var template = _self.breadcrumbTemplate;
        template = template
            .replace(/__name__/gi, value.name || '')
            .replace(/__icon__/gi, value.icon ? '<i class="' + value.icon + '"></i>' : '')
            .replace(/__folderId__/gi, value.id || 0);
        $breadcrumbContainer.append($(template));
    });
    $('.rv-media-container').attr('data-breadcrumb-count', _.size(breadcrumbItems));
};

MediaService.refreshFilter = function refreshFilter () {
    var $rvMediaContainer = $('.rv-media-container');
    var view_in = Helpers.getRequestParams().view_in;
    if (view_in !== 'my_media' && ((view_in !== 'shared' && view_in !== 'shared_with_me' && view_in !== 'public') || Helpers.getRequestParams().folder_id == 0)) {
        $('.rv-media-actions .btn:not([data-type="refresh"]):not(label)').addClass('disabled');
        $rvMediaContainer.attr('data-allow-upload', 'false');
    } else {
        $('.rv-media-actions .btn:not([data-type="refresh"]):not(label)').removeClass('disabled');
        $rvMediaContainer.attr('data-allow-upload', 'true');
    }

    $('.rv-media-actions .btn.js-rv-media-change-filter-group').removeClass('disabled');

    var $empty_trash_btn = $('.rv-media-actions .btn[data-action="empty_trash"]');
    if (view_in === 'trash') {
        $empty_trash_btn.removeClass('hidden').removeClass('disabled');
        if (!_.size(Helpers.getItems())) {
            $empty_trash_btn.addClass('hidden').addClass('disabled');
        }
    } else {
        $empty_trash_btn.addClass('hidden');
    }

    ContextMenuService.destroyContext();
    ContextMenuService.initContext();

    $rvMediaContainer.attr('data-view-in', view_in);
};

var FolderService = function FolderService() {
    this.MediaList = new MediaList();
    this.MediaService = new MediaService();

    $('body').on('shown.bs.modal', '#modal_add_folder', function () {
        $(this).find('.form-add-folder input[type=text]').focus();
    });
};

FolderService.prototype.create = function create (folderName) {
    var _self = this;
    $.ajax({
        url: RV_MEDIA_URL.create_folder,
        type: 'POST',
        data: {
            parent_id: Helpers.getRequestParams().folder_id,
            name: folderName
        },
        dataType: 'json',
        beforeSend: function () {
            Helpers.showAjaxLoading();
        },
        success: function (res) {
            if (res.error) {
                MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
            } else {
                MessageService.showMessage('success', res.message, RV_MEDIA_CONFIG.translations.message.success_header);
                Helpers.resetPagination();
                _self.MediaService.getMedia(true);
                FolderService.closeModal();
            }
        },
        complete: function (data) {
            Helpers.hideAjaxLoading();
        },
        error: function (data) {
            MessageService.handleError(data);
        }
    });
};

FolderService.prototype.changeFolder = function changeFolder (folderId) {
    MediaConfig.request_params.folder_id = folderId;
    Helpers.storeConfig();
    this.MediaService.getMedia(true);
};

FolderService.closeModal = function closeModal () {
    $('#modal_add_folder').modal('hide');
};

var UploadService = function UploadService() {
    this.$body = $('body');

    this.dropZone = null;

    this.uploadUrl = RV_MEDIA_URL.upload_file;

    this.uploadProgressBox = $('.rv-upload-progress');

    this.uploadProgressContainer = $('.rv-upload-progress .rv-upload-progress-table');

    this.uploadProgressTemplate = $('#rv_media_upload_progress_item').html();

    this.totalQueued = 1;

    this.MediaService = new MediaService();

    this.totalError = 0;
};

UploadService.prototype.init = function init () {
    if (_.contains(RV_MEDIA_CONFIG.permissions, 'files.create') && $('.rv-media-items').length > 0) {
        this.setupDropZone();
    }
    this.handleEvents();
};

UploadService.prototype.setupDropZone = function setupDropZone () {
    var _self = this;

    _self.dropZone = new Dropzone(document.querySelector('.rv-media-items'), {
        url: _self.uploadUrl,
        thumbnailWidth: false,
        thumbnailHeight: false,
        parallelUploads: 1,
        autoQueue: true,
        clickable: '.js-dropzone-upload',
        previewTemplate: false,
        previewsContainer: false,
        uploadMultiple: true,
        sending: function (file, xhr, formData) {
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('folder_id', Helpers.getRequestParams().folder_id);
            formData.append('view_in', Helpers.getRequestParams().view_in);
        },
    });

    _self.dropZone.on('addedfile', function (file) {
        file.index = _self.totalQueued;
        _self.totalQueued++;
    });

    _self.dropZone.on('sending', function (file) {
        _self.initProgress(file.name, file.size);
    });

    _self.dropZone.on('success', function (file) {

    });

    _self.dropZone.on('complete', function (file) {
        _self.changeProgressStatus(file);
    });

    _self.dropZone.on('queuecomplete', function () {
        Helpers.resetPagination();
        _self.MediaService.getMedia(true);
        if (_self.totalError === 0) {
            setTimeout(function () {
                $('.rv-upload-progress .close-pane').trigger('click');
            }, 5000);
        }
    });
};

UploadService.prototype.handleEvents = function handleEvents () {
    var _self = this;
    /**
     * Close upload progress pane
     */
    _self.$body.on('click', '.rv-upload-progress .close-pane', function (event) {
        event.preventDefault();
        $('.rv-upload-progress').addClass('hide-the-pane');
        _self.totalError = 0;
        setTimeout(function () {
            $('.rv-upload-progress li').remove();
            _self.totalQueued = 1;
        }, 300);
    });
};

UploadService.prototype.initProgress = function initProgress ($fileName, $fileSize) {
    var template = this.uploadProgressTemplate
            .replace(/__fileName__/gi, $fileName)
            .replace(/__fileSize__/gi, UploadService.formatFileSize($fileSize))
            .replace(/__status__/gi, 'warning')
            .replace(/__message__/gi, 'Uploading');
    this.uploadProgressContainer.append(template);
    this.uploadProgressBox.removeClass('hide-the-pane');
    this.uploadProgressBox.find('.panel-body')
        .animate({scrollTop: this.uploadProgressContainer.height()}, 150);
};

UploadService.prototype.changeProgressStatus = function changeProgressStatus (file) {
    var _self = this;
    var $progressLine = _self.uploadProgressContainer.find('li:nth-child(' + (file.index) + ')');
    var $label = $progressLine.find('.label');
    $label.removeClass('label-success label-danger label-warning');

    var response = Helpers.jsonDecode(file.xhr.responseText || '', {});

    _self.totalError = _self.totalError + (response.error === true || file.status === 'error' ? 1 : 0);

    $label.addClass(response.error === true || file.status === 'error' ? 'label-danger' : 'label-success');
    $label.html(response.error === true || file.status === 'error' ? 'Error' : 'Uploaded');
    if (file.status === 'error') {
        if (file.xhr.status === 422) {
            var error_html = '';
            $.each(response, function (key, item) {
                error_html += '<span class="text-danger">' + item + '</span><br>';
            });
            $progressLine.find('.file-error').html(error_html);
        } else if (file.xhr.status === 500) {
            $progressLine.find('.file-error').html('<span class="text-danger">' + file.xhr.statusText + '</span>');
        }
    } else if (response.error) {
        $progressLine.find('.file-error').html('<span class="text-danger">' + response.message + '</span>');
    } else {
        Helpers.addToRecent(response.data.id);
    }
};

UploadService.formatFileSize = function formatFileSize (bytes, si) {
        if ( si === void 0 ) si = false;

    var thresh = si ? 1000 : 1024;
    if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
    }
    var units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    } while (Math.abs(bytes) >= thresh && u < units.length - 1);
    return bytes.toFixed(1) + ' ' + units[u];
};

var ExternalServiceConfig = {
    youtube: {
        api_key: "AIzaSyCV4fmfdgsValGNR3sc-0W3cbpEZ8uOd60"
    }
};

var Youtube = function Youtube() {
    this.MediaService = new MediaService();

    this.$body = $('body');

    this.$modal = $('#modal_add_from_youtube');

    var _self = this;

    this.setMessage(RV_MEDIA_CONFIG.translations.add_from.youtube.original_msg);

    this.$modal.on('hidden.bs.modal', function () {
        _self.setMessage(RV_MEDIA_CONFIG.translations.add_from.youtube.original_msg);
    });

    this.$body.on('click', '#modal_add_from_youtube .rv-btn-add-youtube-url', function (event) {
        event.preventDefault();

        _self.checkYouTubeVideo($(this).closest('#modal_add_from_youtube').find('.rv-youtube-url'));
    });
};

Youtube.validateYouTubeLink = function validateYouTubeLink (url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
};

Youtube.getYouTubeId = function getYouTubeId (url) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[2].length === 11) {
        return match[2];
    }
    return null;
};

Youtube.getYoutubePlaylistId = function getYoutubePlaylistId (url) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?list=|\&list=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match) {
        return match[2];
    }
    return null;
};

Youtube.prototype.setMessage = function setMessage (msg) {
    this.$modal.find('.modal-notice').html(msg);
};

Youtube.prototype.checkYouTubeVideo = function checkYouTubeVideo ($input) {
    var _self = this;
    if (!Youtube.validateYouTubeLink($input.val()) || !ExternalServiceConfig.youtube.api_key) {
        if (ExternalServiceConfig.youtube.api_key) {
            _self.setMessage(RV_MEDIA_CONFIG.translations.add_from.youtube.invalid_url_msg);
        } else {
            _self.setMessage(RV_MEDIA_CONFIG.translations.add_from.youtube.no_api_key_msg);
        }
    } else {
        var youtubeId = Youtube.getYouTubeId($input.val());
        var requestUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' + youtubeId;
        var isPlaylist = _self.$modal.find('.custom-checkbox input[type="checkbox"]').is(':checked');

        if (isPlaylist) {
            youtubeId = Youtube.getYoutubePlaylistId($input.val());
            requestUrl = 'https://www.googleapis.com/youtube/v3/playlistItems?playlistId=' + youtubeId;
        }

        $.ajax({
            url: requestUrl + '&key=' + ExternalServiceConfig.youtube.api_key + '&part=snippet',
            type: "GET",
            success: function (data) {
                if (isPlaylist) {
                    playlistVideoCallback(data, $input.val());
                } else {
                    singleVideoCallback(data, $input.val());
                }
            },
            error: function (data) {
                _self.setMessage(RV_MEDIA_CONFIG.translations.add_from.youtube.error_msg);
            }
        });
    }

    function singleVideoCallback(data, url) {
        $.ajax({
            url: RV_MEDIA_URL.add_external_service,
            type: "POST",
            dataType: 'json',
            data: {
                type: 'youtube',
                name: data.items[0].snippet.title,
                folder_id: Helpers.getRequestParams().folder_id,
                url: url,
                options: {
                    thumb: 'https://img.youtube.com/vi/' + data.items[0].id + '/maxresdefault.jpg'
                }
            },
            success: function (res) {
                if (res.error) {
                    MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
                } else {
                    MessageService.showMessage('success', res.message, RV_MEDIA_CONFIG.translations.message.success_header);
                    _self.MediaService.getMedia(true);
                }
            },
            error: function (data) {
                MessageService.handleError(data);
            }
        });
        _self.$modal.modal('hide');
    }

    function playlistVideoCallback(data, url) {
        _self.$modal.modal('hide');
    }
};

var ExternalServices = function ExternalServices() {
    new Youtube();
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

var MediaManagement = function MediaManagement() {
    this.MediaService = new MediaService();
    this.UploadService = new UploadService();
    this.FolderService = new FolderService();

    new ExternalServices();

    this.$body = $('body');
};

MediaManagement.prototype.init = function init () {
    Helpers.resetPagination();
    this.setupLayout();

    this.handleMediaList();
    this.changeViewType();
    this.changeFilter();
    this.search();
    this.handleActions();

    this.UploadService.init();

    this.handleModals();
    this.scrollGetMore();
};

MediaManagement.prototype.setupLayout = function setupLayout () {
    /**
     * Sidebar
     */
    var $current_filter = $('.js-rv-media-change-filter[data-type="filter"][data-value="' + Helpers.getRequestParams().filter + '"]');

    $current_filter.closest('li')
        .addClass('active')
        .closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current_filter.html() + ')');

    var $current_view_in = $('.js-rv-media-change-filter[data-type="view_in"][data-value="' + Helpers.getRequestParams().view_in + '"]');

    $current_view_in.closest('li')
        .addClass('active')
        .closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current_view_in.html() + ')');

    if (Helpers.isUseInModal()) {
        $('.rv-media-footer').removeClass('hidden');
    }

    /**
     * Sort
     */
    $('.js-rv-media-change-filter[data-type="sort_by"][data-value="' + Helpers.getRequestParams().sort_by + '"]')
        .closest('li')
        .addClass('active');

    /**
     * Details pane
     */
    var $mediaDetailsCheckbox = $('#media_details_collapse');
    $mediaDetailsCheckbox.prop('checked', MediaConfig.hide_details_pane || false);
    setTimeout(function () {
        $('.rv-media-details').removeClass('hidden');
    }, 300);
    $mediaDetailsCheckbox.on('change', function (event) {
        event.preventDefault();
        MediaConfig.hide_details_pane = $(this).is(':checked');
        Helpers.storeConfig();
    });

    $(document).on('click', 'button[data-dismiss-modal]', function() {
        var modal = $(this).data('dismiss-modal');
        $(modal).modal('hide');
    });
};

MediaManagement.prototype.handleMediaList = function handleMediaList () {
    var _self = this;

    /*Ctrl key in Windows*/
    var ctrl_key = false;

    /*Command key in MAC*/
    var meta_key = false;

    /*Shift key*/
    var shift_key = false;

    $(document).on('keyup keydown', function (e) {
        /*User hold ctrl key*/
        ctrl_key = e.ctrlKey;
        /*User hold command key*/
        meta_key = e.metaKey;
        /*User hold shift key*/
        shift_key = e.shiftKey;
    });

    _self.$body
        .on('click', '.js-media-list-title', function (event) {
            event.preventDefault();
            var $current = $(this);

            if (shift_key) {
                var firstItem = _.first(Helpers.getSelectedItems());
                if (firstItem) {
                    var firstIndex = firstItem.index_key;
                    var currentIndex = $current.index();
                    $('.rv-media-items li').each(function (index) {
                        if (index > firstIndex && index <= currentIndex) {
                            $(this).find('input[type=checkbox]').prop('checked', true);
                        }
                    });
                }
            } else {
                if (!ctrl_key && !meta_key) {
                    $current.closest('.rv-media-items').find('input[type=checkbox]').prop('checked', false);
                }
            }

            var $lineCheckBox = $current.find('input[type=checkbox]');
            $lineCheckBox.prop('checked', true);
            ActionsService.handleDropdown();

            _self.MediaService.getFileDetails($current.data());
        })
        .on('dblclick', '.js-media-list-title', function (event) {
            event.preventDefault();
            var data = $(this).data();
            if (data.is_folder === true) {
                Helpers.resetPagination();
                _self.FolderService.changeFolder(data.id);
            } else {
                if (!Helpers.isUseInModal()) {
                    ActionsService.handlePreview();
                } else if (Helpers.getConfigs().request_params.view_in !== 'trash') {
                    var selectedFiles = Helpers.getSelectedFiles();
                    if (_.size(selectedFiles) > 0) {
                        EditorService.editorSelectFile(selectedFiles);
                    }
                }
            }
        })
        .on('dblclick', '.js-up-one-level', function (event) {
            event.preventDefault();
            var count = $('.rv-media-breadcrumb .breadcrumb li').length;
            $('.rv-media-breadcrumb .breadcrumb li:nth-child(' + (count - 1) + ') a').trigger('click');
        })
        .on('contextmenu', '.js-context-menu', function (e) {
            if (!$(this).find('input[type=checkbox]').is(':checked')) {
                $(this).trigger('click');
            }
        })
        .on('click contextmenu', '.rv-media-items', function (e) {
            if (!_.size(e.target.closest('.js-context-menu'))) {
                $('.rv-media-items input[type="checkbox"]').prop('checked', false);
                $('.rv-dropdown-actions').addClass('disabled');
                _self.MediaService.getFileDetails({
                    icon: 'fa fa-picture-o',
                    nothing_selected: '',
                });
            }
        })
    ;
};

MediaManagement.prototype.changeViewType = function changeViewType () {
    var _self = this;
    _self.$body.on('click', '.js-rv-media-change-view-type .btn', function (event) {
        event.preventDefault();
        var $current = $(this);
        if ($current.hasClass('active')) {
            return;
        }
        $current.closest('.js-rv-media-change-view-type').find('.btn').removeClass('active');
        $current.addClass('active');

        MediaConfig.request_params.view_type = $current.data('type');

        if ($current.data('type') === 'trash') {
            $(document).find('.js-insert-to-editor').prop('disabled', true);
        } else {
            $(document).find('.js-insert-to-editor').prop('disabled', false);
        }

        Helpers.storeConfig();

        _self.MediaService.getMedia(true, true);
    });
    $('.js-rv-media-change-view-type .btn[data-type="' + Helpers.getRequestParams().view_type + '"]').trigger('click');

    this.bindIntegrateModalEvents();
};

MediaManagement.prototype.changeFilter = function changeFilter () {
    var _self = this;
    _self.$body.on('click', '.js-rv-media-change-filter', function (event) {
        event.preventDefault();
        if (!Helpers.isOnAjaxLoading()) {
            var $current = $(this);
            var $parent = $current.closest('ul');
            var data = $current.data();

            MediaConfig.request_params[data.type] = data.value;

            if (data.type === 'view_in') {
                MediaConfig.request_params.folder_id = 0;
                if (data.value === 'trash') {
                    $(document).find('.js-insert-to-editor').prop('disabled', true);
                } else {
                    $(document).find('.js-insert-to-editor').prop('disabled', false);
                }
            }

            $current.closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current.html() + ')');

            Helpers.storeConfig();
            MediaService.refreshFilter();

            Helpers.resetPagination();
            _self.MediaService.getMedia(true);

            $parent.find('> li').removeClass('active');
            $current.closest('li').addClass('active');
        }
    });
};

MediaManagement.prototype.search = function search () {
    var _self = this;
    $('.input-search-wrapper input[type="text"]').val(Helpers.getRequestParams().search || '');
    _self.$body.on('submit', '.input-search-wrapper', function (event) {
        event.preventDefault();
        MediaConfig.request_params.search = $(this).find('input[type="text"]').val();

        Helpers.storeConfig();
        _self.resetPagination();
        _self.MediaService.getMedia(true);
    });
};

MediaManagement.prototype.handleActions = function handleActions () {
    var _self = this;

    _self.$body
        .on('click', '.rv-media-actions .js-change-action[data-type="refresh"]', function (event) {
            event.preventDefault();

            Helpers.resetPagination();

            var ele_options = typeof window.rvMedia.$el !== 'undefined' ? window.rvMedia.$el.data('rv-media') : undefined;
            if (typeof ele_options !== 'undefined' && ele_options.length > 0 && typeof ele_options[0].selected_file_id !== 'undefined') {
                _self.MediaService.getMedia(true, true);
            } else
                { _self.MediaService.getMedia(true, false); }
        })
        .on('click', '.rv-media-items li.no-items', function (event) {
            event.preventDefault();
            $('.rv-media-header .rv-media-top-header .rv-media-actions .js-dropzone-upload').trigger('click');
        })
        .on('submit', '.form-add-folder', function (event) {
            event.preventDefault();
            var $input = $(this).find('input[type=text]');
            var folderName = $input.val();
            _self.FolderService.create(folderName);
            $input.val('');
        })
        .on('click', '.js-change-folder', function (event) {
            event.preventDefault();
            var folderId = $(this).data('folder');
            Helpers.resetPagination();
            _self.FolderService.changeFolder(folderId);
        })
        .on('click', '.js-files-action', function (event) {
            event.preventDefault();
            ActionsService.handleGlobalAction($(this).data('action'), function (res) {
                Helpers.resetPagination();
                _self.MediaService.getMedia(true);
            });
        })
    ;
};

MediaManagement.prototype.handleModals = function handleModals () {
    var _self = this;
    /*Rename files*/
    _self.$body.on('show.bs.modal', '#modal_rename_items', function (event) {
        ActionsService.renderRenameItems();
    });
    _self.$body.on('submit', '#modal_rename_items .form-rename', function (event) {
        event.preventDefault();
        var items = [];
        var $form = $(this);

        $('#modal_rename_items .form-control').each(function () {
            var $current = $(this);
            var data = $current.closest('.form-group').data();
            data.name = $current.val();
            items.push(data);
        });

        ActionsService.processAction({
            action: $form.data('action'),
            selected: items
        }, function (res) {
            if (!res.error) {
                $form.closest('.modal').modal('hide');
                _self.MediaService.getMedia(true);
            } else {
                $('#modal_rename_items .form-group').each(function () {
                    var $current = $(this);
                    if (_.contains(res.data, $current.data('id'))) {
                        $current.addClass('has-error');
                    } else {
                        $current.removeClass('has-error');
                    }
                });
            }
        });
    });

    /*Delete files*/
    _self.$body.on('submit', '.form-delete-items', function (event) {
        event.preventDefault();
        var items = [];
        var $form = $(this);

        _.each(Helpers.getSelectedItems(), function (value) {
            items.push({
                id: value.id,
                is_folder: value.is_folder,
            });
        });

        ActionsService.processAction({
            action: $form.data('action'),
            selected: items
        }, function (res) {
            $form.closest('.modal').modal('hide');
            if (!res.error) {
                _self.MediaService.getMedia(true);
            }
        });
    });

    /*Empty trash*/
    _self.$body.on('submit', '#modal_empty_trash .rv-form', function (event) {
        event.preventDefault();
        var $form = $(this);

        ActionsService.processAction({
            action: $form.data('action')
        }, function (res) {
            $form.closest('.modal').modal('hide');
            _self.MediaService.getMedia(true);
        });
    });

    /*Share files*/
    var users = [];
    var $shareOption = $('#share_option');
    var $shareToUsers = $('#share_to_users');
    $shareOption.on('change', function (event) {
        event.preventDefault();
        if ($(this).val() === 'user') {
            $shareToUsers.closest('.form-group').removeClass('hidden');
        } else {
            $shareToUsers.closest('.form-group').addClass('hidden');
        }
    }).trigger('change');
    _self.$body
        .on('show.bs.modal', '#modal_share_items', function (event) {
            $shareOption.val('no_share').trigger('change');
            $shareToUsers.val('');

            var selectedItems = Helpers.getSelectedItems();

            if (_.size(selectedItems) !== 1) {

                var is_public = true;
                $.each(selectedItems, function (index, el) {
                    if (el.is_public == 0) {
                        is_public = false;
                    }
                });

                if (is_public) {
                    $shareOption.val('everyone').trigger('change');
                } else {
                    $.ajax({
                        url: RV_MEDIA_URL.get_users,
                        type: 'GET',
                        dataType: 'json',
                        success: function (res) {
                            if (!res.error) {
                                $shareToUsers.html('');
                                users = res.data;
                                _.each(users, function (value) {
                                    var option = '<option value="' + value.id + '">' + value.name + '</option>';
                                    $shareToUsers.append(option);
                                });
                            } else {
                                MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
                            }
                        },
                        error: function (data) {
                            MessageService.handleError(data);
                        }
                    });
                }
            } else {
                var selectedItem = _.first(selectedItems);

                if (selectedItem.is_public) {
                    $shareOption.val('everyone').trigger('change');
                } else {
                    $.ajax({
                        url: RV_MEDIA_URL.get_shared_users,
                        type: 'GET',
                        data: {
                            share_id: selectedItem.id,
                            is_folder: selectedItem.is_folder,
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (!res.error) {
                                $shareToUsers.html('');
                                users = res.data.users;
                                var totalSelected = 0;
                                _.each(users, function (value) {
                                    var isSelected = value.is_selected;
                                    if (isSelected) {
                                        totalSelected++;
                                    }
                                    var option = '<option value="' + value.id + '" ' + (isSelected ? 'selected' : '') + '>' + value.name + '</option>';
                                    $shareToUsers.append(option);
                                });
                                if (totalSelected > 0) {
                                    $shareOption.val('user').trigger('change');
                                }
                            } else {
                                MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
                            }
                        },
                        error: function (data) {
                            MessageService.handleError(data);
                        }
                    });
                }
            }
        })
        .on('submit', '#modal_share_items .rv-form', function (event) {
            event.preventDefault();
            var $form = $(this);

            var items = [];
            _.each(Helpers.getSelectedItems(), function (value) {
                items.push({
                    id: value.id,
                    is_folder: value.is_folder,
                });
            });

            ActionsService.processAction({
                action: $form.data('action'),
                selected: items,
                share_option: $shareOption.val(),
                users: $shareToUsers.val()
            }, function (res) {
                $form.closest('.modal').modal('hide');
                _self.MediaService.getMedia(true);
            });
        })
        .on('submit', '#modal_set_focus_point .rv-form', function (event) {
            event.preventDefault();
            var $form = $(this);

            var items = [];
            var selected_items = Helpers.getSelectedItems();
            _.each(selected_items, function (value) {
                items.push({
                    id: value.id,
                    is_folder: value.is_folder
                });
            });

            ActionsService.processAction({
                action: $form.data('action'),
                selected: items,
                data_attribute: $('.helper-tool-data-attr').val(),
                css_bg_position: $('.helper-tool-css3-val').val(),
                retice_css: $('.helper-tool-reticle-css').val()
            }, function (res) {
                $form.closest('.modal').modal('hide');
                _.each(selected_items, function (value) {
                    if (value.id === res.data.id) {
                        $('.js-media-list-title[data-id=' + value.id + ']').data(res.data);
                    }
                });
            });
        });

    if (MediaConfig.request_params.view_in === 'trash') {
        $(document).find('.js-insert-to-editor').prop('disabled', true);
    } else {
        $(document).find('.js-insert-to-editor').prop('disabled', false);
    }

    this.bindIntegrateModalEvents();
};

MediaManagement.prototype.checkFileTypeSelect = function checkFileTypeSelect (selectedFiles) {
    if (typeof window.rvMedia.$el !== 'undefined') {
        var firstItem = _.first(selectedFiles);
        var ele_options = window.rvMedia.$el.data('rv-media');
        if (typeof ele_options !== 'undefined' && typeof ele_options[0] !== 'undefined' && typeof ele_options[0].file_type !== 'undefined' && firstItem !== 'undefined'
            && firstItem.type !== 'undefined' && !ele_options[0].file_type.match(firstItem.type)) {
            return false;
        }
    }
    return true;
};

MediaManagement.prototype.bindIntegrateModalEvents = function bindIntegrateModalEvents () {
    var $main_modal = $('#rv_media_modal');
    var _self = this;
    $main_modal.off('click', '.js-insert-to-editor').on('click', '.js-insert-to-editor', function (event) {
        event.preventDefault();
        var selectedFiles = Helpers.getSelectedFiles();
        if (_.size(selectedFiles) > 0) {
            window.rvMedia.options.onSelectFiles(selectedFiles, window.rvMedia.$el);
            if (_self.checkFileTypeSelect(selectedFiles)) {
                $main_modal.find('.close').trigger('click');
            }
        }
    });

    $main_modal.off('dblclick', '.js-media-list-title').on('dblclick', '.js-media-list-title', function (event) {
        event.preventDefault();
        if (Helpers.getConfigs().request_params.view_in !== 'trash') {
            var selectedFiles = Helpers.getSelectedFiles();
            if (_.size(selectedFiles) > 0) {
                window.rvMedia.options.onSelectFiles(selectedFiles, window.rvMedia.$el);
                if (_self.checkFileTypeSelect(selectedFiles)) {
                    $main_modal.find('.close').trigger('click');
                }
            }
        } else {
            ActionsService.handlePreview();
        }
    });
};

MediaManagement.setupSecurity = function setupSecurity () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
};



//scroll get more media
MediaManagement.prototype.scrollGetMore = function scrollGetMore () {
    var _self = this;
    $('.rv-media-main .rv-media-items').bind('DOMMouseScroll mousewheel', function (e) {
        if (e.originalEvent.detail > 0 || e.originalEvent.wheelDelta < 0) {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 350) {
                if (typeof RV_MEDIA_CONFIG.pagination != 'undefined' && RV_MEDIA_CONFIG.pagination.has_more) {
                    _self.MediaService.getMedia(false, false, true);
                } else {
                    return;
                }
            }
        }
    });
};

$(document).ready(function () {
    window.rvMedia = window.rvMedia || {};

    MediaManagement.setupSecurity();
    new MediaManagement().init();
});

}());
//# sourceMappingURL=media.js.map
