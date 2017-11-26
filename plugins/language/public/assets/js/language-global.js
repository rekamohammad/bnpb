$(document).ready(function () {

    var language_choice_select = $('#post_lang_choice');
    language_choice_select.data('prev', language_choice_select.val());

    language_choice_select.on('change', function() {
        $('.change_to_language_text').text($(this).find('option:selected').text());
        $('#confirm-change-language-modal').modal('show');
    });

    $('#confirm-change-language-modal .btn-primary').on('click', function (event) {
        event.preventDefault();
        language_choice_select.val(language_choice_select.data('prev')).trigger('change');
        $('#confirm-change-language-modal').modal('hide');
    });

    $('#confirm-change-language-button').on('click', function (event) {
        event.preventDefault();
        var _self = language_choice_select;
        var flag_path = $('#language_flag_path').val();

        $.ajax({
            url: $('div[data-change-language-route]').data('change-language-route'),
            data: {
                current_language: _self.val(),
                content_id: $('#content_id').val(),
                reference: $('#reference').val(),
                created_from: $('#created_from').val()
            },
            type: 'POST',
            success: function(data) {
                $('.active-language').html('<img src="' + flag_path + _self.find('option:selected').data('flag') + '.png" title="' + _self.find('option:selected').text() + '" alt="' + _self.find('option:selected').text() + '" />');
                if (!data.error) {
                    $('.current_language_text').text(_self.find('option:selected').text());
                    var html = '';
                    $.each(data.data, function (index, el) {
                        html += '<img src="' + flag_path + el.flag + '.png" title="' + el.name + '" alt="' + el.name + '">';
                        if (el.content_id) {
                            html += '<a href="' + $('#route_edit').val() + '"> ' + el.name + ' <i class="fa fa-edit"></i> </a><br />';
                        } else {
                            html += '<a href="' + $('#route_create').val() + '?from=' + $('#content_id').val() +'&lang=' + index + '"> ' + el.name + ' <i class="fa fa-plus"></i> </a><br />';
                        }
                    });

                    $('#list-others-language').html(html);
                    $('#confirm-change-language-modal').modal('hide');
                    language_choice_select.data('prev', language_choice_select.val());
                }
            },
            error: function(data) {
                Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
            }
        });
    });
});

var BBLanguage = {
    init: function () {
        $(document).on('click', '.change-data-language-item', function (event) {
            event.preventDefault();
            window.location.href = $(this).find('a span').data('href');
        });
    }
};

$(document).ready(function () {
    BBLanguage.init();
});
//# sourceMappingURL=language-global.js.map
