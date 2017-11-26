jQuery(document).ready(function () {

    $('input[type=checkbox]').uniform();

    $('#auto-checkboxes li').tree({
        onCheck: {
            node: 'expand'
        },
        onUncheck: {
            node: 'collapse'
        },
        dnd: false,
        selectable: false
    });
    $('#mainNode .checker').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }
        });
        jQuery.uniform.update(set);
    });

});

var expandCollapseTree = function (elementId) {

    if ($('#' + elementId).hasClass('collapsed')) {
        $('#auto-checkboxes li').tree('expand', $('#' + elementId));
    } else {
        $('#auto-checkboxes li').tree('collapse', $('#' + elementId));
    }

    event.stopImmediatePropagation();
    return false;


};

//# sourceMappingURL=feature.js.map
