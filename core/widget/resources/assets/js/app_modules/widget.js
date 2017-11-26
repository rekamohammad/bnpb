$(document).ready(function () {
    var list_widgets = [{
        name: 'wrap-widgets',
        pull: 'clone',
        put: false
    }];

    $.each($('.sidebar-item'), function (index, el) {
        list_widgets.push({name: 'wrap-widgets', pull: true, put: true});
    });

    list_widgets.forEach(function (groupOpts, i) {
        Sortable.create(document.getElementById('wrap-widget-' + (i + 1)), {
            sort: (i != 0),
            group: groupOpts,
            delay: 0, // time in milliseconds to define when the sorting should start
            disabled: false, // Disables the sortable if set to true.
            store: null, // @see Store
            animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
            handle: '.widget-handle',
            ghostClass: "sortable-ghost", // Class name for the drop placeholder
            chosenClass: "sortable-chosen", // Class name for the chosen item
            dataIdAttr: 'data-id',

            forceFallback: false, // ignore the HTML5 DnD behaviour and force the fallback to kick in
            fallbackClass: "sortable-fallback", // Class name for the cloned DOM Element when using forceFallback
            fallbackOnBody: false,  // Appends the cloned DOM Element into the Document's Body

            scroll: true, // or HTMLElement
            scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
            scrollSpeed: 10, // px

            // dragging ended
            onEnd: function (evt) {
                saveWidget($(evt.item).closest('.sidebar-item'));
            }
        });
    });

    var widget_wrap = $('#wrap-widgets');
    widget_wrap.on('click', '.widget-control-delete', function (event) {
        event.preventDefault();
        var widget = $(this).closest('li');
        $(this).html('<i class="fa fa-spinner fa-spin"></i>' + $(this).text());
        $.ajax({
            type: 'POST',
            cache: false,
            url: BWidget.routes.delete,
            data: {
                widget_id: widget.data('id'),
                position: widget.data('position'),
                sidebar_id: $(this).closest('.sidebar-item').data('id')
            },
            beforeSend: function() {
                Botble.showNotice('info', Botble.languages.notices_msg.processing_request);
            },
            success: function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                    widget.fadeOut().remove();
                }
                widget.find('.widget-control-delete i').remove();
            },
            error: function (data) {
                Botble.handleError(data);
                widget.find('.widget-control-delete i').remove();
            }
        });

    });

    widget_wrap.on('click', '#added-widget .widget-handle', function () {
        $(this).closest('li').find('.widget-content').slideToggle(300);
        $(this).find('.fa').toggleClass('fa-caret-up');
        $(this).find('.fa').toggleClass('fa-caret-down');
    });

    widget_wrap.on('click', '.widget_save', function (event) {
        event.preventDefault();
        $(this).html('<i class="fa fa-spinner fa-spin"></i>' + $(this).text());
        saveWidget($(this).closest('.sidebar-item'));
    });

    function saveWidget(parentElement) {
        if (parentElement.length > 0) {
            var items = [];
            $.each(parentElement.find('li'), function (index, widget) {
                items.push($(widget).find('form').serialize());
            });
            $.ajax({
                type: 'POST',
                cache: false,
                url: BWidget.routes.save_widgets_sidebar,
                data: {
                    items: items,
                    sidebar_id: parentElement.data('id')
                },
                beforeSend: function() {
                    Botble.showNotice('info', Botble.languages.notices_msg.processing_request);
                },
                success: function (data) {
                    if (data.error) {
                        Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                    } else {
                        parentElement.find('ul').html(data.data);
                        $('.styled').uniform();
                        Botble.callScroll($('.list-page-select-widget'));
                        Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                    }

                    parentElement.find('.widget_save i').remove();
                },
                error: function (data) {
                    Botble.handleError(data);
                    parentElement.find('.widget_save i').remove();
                }
            });
        }
    }

    Botble.callScroll($('.list-page-select-widget'));
});
