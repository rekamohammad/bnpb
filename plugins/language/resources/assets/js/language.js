var BLanguage = BLanguage || {};

BLanguage.formatState = function (state) {
    if (!state.id) {
        return state.text;
    }
    return $('<span><img src="' + $('#language_flag_path').val() + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>');
};

BLanguage.bindEventToElement = function () {
    if (jQuery().select2) {

        $('.select-search-language').select2({
            width: '100%',
            templateResult: BLanguage.formatState,
            templateSelection: BLanguage.formatState
        });
    }

    var table_language = $('.table-language');

    $(document).on('change', '#language_id', function () {
        var language = $(this).find('option:selected').data('language');
        if (typeof language != 'undefined' && language.length > 0) {
            $('#lang_name').val(language[2]);
            $('#lang_locale').val(language[0]);
            $('#lang_code').val(language[1]);
            $('#flag_list').val(language[4]).trigger('change');
            $('.lang_is_' + language[3]).prop('checked', true);
            $('#btn-language-submit-edit').prop('id', 'btn-language-submit').text('Add new language');
        }
    });

    //Botble.callScroll($('.table-language'));

    $(document).on('click', '#btn-language-submit', function (event) {
        event.preventDefault();
        var name = $('#lang_name').val();
        var locale = $('#lang_locale').val();
        var code = $('#lang_code').val();
        var flag = $('#flag_list').val();
        var order = $('#lang_order').val();
        var is_rtl = $('.lang_is_rtl').prop('checked') ? 1 : 0;
        BLanguage.createOrUpdateLanguage(0, name, locale, code, flag, order, is_rtl, 0);
    });

    $(document).on('click', '#btn-language-submit-edit', function (event) {
        event.preventDefault();
        var id = $('#lang_id').val();
        var name = $('#lang_name').val();
        var locale = $('#lang_locale').val();
        var code = $('#lang_code').val();
        var flag = $('#flag_list').val();
        var order = $('#lang_order').val();
        var is_rtl = $('.lang_is_rtl').prop('checked') ? 1 : 0;
        BLanguage.createOrUpdateLanguage(id, name, locale, code, flag, order, is_rtl, 1);
    });

    table_language.on('click', '.deleteDialog', function (event) {
        event.preventDefault();

        $('#delete-crud-entry').data('section', $(this).data('section'));
        $('#delete-crud-modal').modal('show');
    });

    $('#delete-crud-entry').on('click', function (event) {
        event.preventDefault();
        $('#delete-crud-modal').modal('hide');

        var deleteURL = $(this).data('section');

        $.ajax({
            url: deleteURL,
            type: 'GET',
            success: function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    if (data.data) {
                        table_language.find('i[data-id=' + data.data + ']').unwrap();
                        $('.tooltip').remove();
                    }
                    table_language.find('a[data-section="' + deleteURL + '"]').closest('tr').remove();
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                }
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });

    table_language.on('click', '.set-language-default', function (event) {
        event.preventDefault();
        var _self = $(this);

        $.ajax({
            url: _self.data('section'),
            type: 'GET',
            success: function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    var star = table_language.find('td > i');
                    star.replaceWith('<a data-section="' + BLanguage.routes.set_default + '?lang_id=' + star.data('id') + '" class="set-language-default tip" data-original-title="Choose ' + star.data('name') + ' as default language">' + star.closest('td').html() + '</a>');
                    _self.find('i').unwrap();
                    $('.tooltip').remove();
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                }
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });

    table_language.on('click', '.edit-language-button', function (event) {
        event.preventDefault();
        var _self = $(this);

        $.ajax({
            url: BLanguage.routes.get_language + '?lang_id=' + _self.data('id'),
            type: 'GET',
            success: function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    var language = data.data;
                    $('#lang_id').val(language.lang_id);
                    $('#lang_name').val(language.lang_name);
                    $('#lang_locale').val(language.lang_locale);
                    $('#lang_code').val(language.lang_code);
                    $('#flag_list').val(language.lang_flag).trigger('change');
                    if (language.lang_rtl) {
                        $('.lang_is_rtl').prop('checked', true);
                    }
                    $('#lang_order').val(language.lang_order);

                    $('#btn-language-submit').prop('id', 'btn-language-submit-edit').text('Update');
                }
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });
};

BLanguage.createOrUpdateLanguage = function (id, name, locale, code, flag, order, is_rtl, edit) {
    var url = BLanguage.routes.store;
    if (edit) {
        url = BLanguage.routes.edit + '?lang_code=' + code;
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            lang_id: id,
            lang_name: name,
            lang_locale: locale,
            lang_code: code,
            lang_flag: flag,
            lang_order: order,
            lang_is_rtl: is_rtl
        },
        success: function (data) {
            if (data.error) {
                Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
            } else {
                if (edit) {
                    $('.table-language').find('tr[data-id=' + id + ']').replaceWith(data.data);
                } else {
                    $('.table-language').append(data.data);
                }
                Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
            }

            $('#language_id').val('').trigger('change');
            $('#lang_name').val('');
            $('#lang_locale').val('');
            $('#lang_code').val('');
            $('#flag_list').val('').trigger('change');
            $('.lang_is_ltr').prop('checked', true);

            $('#btn-language-submit-edit').prop('id', 'btn-language-submit').text('Add new language');
        },
        error: function (data) {
            Botble.handleError(data);
        }
    });
};

$(document).ready(function () {
    BLanguage.bindEventToElement();
});