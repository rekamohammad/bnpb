var route = $('div[data-tag-route]').data('tag-route');
var tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
        url: route,
        filter: function (list) {
            return $.map(list, function (tag) {
                return {name: tag};
            });
        }
    }
});
tags.initialize();

$('#tags').tagsinput({
    typeaheadjs: {
        name: 'tags',
        displayKey: 'name',
        valueKey: 'name',
        source: tags.ttAdapter()
    }
});