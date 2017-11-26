import {ActionsService} from './ActionsService';
import {Helpers} from '../Helpers/Helpers';

export class ContextMenuService {
    static initContext() {
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
    }

    static _fileContextMenu() {
        let items = {
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
            })
        });

        let except = [];

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

        let hasFolderSelected = Helpers.getSelectedFolder().length > 0;

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

        let selectedFiles = Helpers.getSelectedFiles();
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

        let can_preview = false;
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
    }

    static _folderContextMenu() {
        let items = ContextMenuService._fileContextMenu();

        items.preview = undefined;
        items.set_focus_point = undefined;
        items.copy_link = undefined;

        return items;
    }

    static destroyContext() {
        if (jQuery().contextMenu) {
            $.contextMenu('destroy');
        }
    }
}
