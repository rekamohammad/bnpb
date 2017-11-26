(function (exports) {
'use strict';

(function ($, DataTable) {
    "use strict";

    var _buildUrl = function (dt, action) {
        var url = dt.ajax.url() || '';
        var params = dt.ajax.params();
        params.action = action;

        return url + '?' + $.param(params);
    };

    DataTable.ext.buttons.excel = {
        className: 'buttons-excel',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.excel', Botble.languages.tables.excel);
        },

        action: function (e, dt) {
            window.location = _buildUrl(dt, 'excel');
        }
    };

    DataTable.ext.buttons.export = {
        extend: 'collection',

        className: 'buttons-export',

        text: function (dt) {
            return '<i class="fa fa-download"></i> ' + dt.i18n('buttons.export', Botble.languages.tables.export) + '&nbsp;<span class="caret"/>';
        },

        buttons: ['csv', 'excel', 'pdf']
    };

    DataTable.ext.buttons.csv = {
        className: 'buttons-csv',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.csv', Botble.languages.tables.csv);
        },

        action: function (e, dt) {
            window.location = _buildUrl(dt, 'csv');
        }
    };

    DataTable.ext.buttons.pdf = {
        className: 'buttons-pdf',

        text: function (dt) {
            return '<i class="fa fa-file-pdf-o"></i> ' + dt.i18n('buttons.pdf', Botble.languages.tables.pdf);
        },

        action: function (e, dt) {
            window.location = _buildUrl(dt, 'pdf');
        }
    };

    DataTable.ext.buttons.print = {
        className: 'buttons-print',

        text: function (dt) {
            return '<i class="fa fa-print"></i> ' + dt.i18n('buttons.print', Botble.languages.tables.print);
        },

        action: function (e, dt) {
            window.location = _buildUrl(dt, 'print');
        }
    };

    DataTable.ext.buttons.reset = {
        className: 'buttons-reset',

        text: function (dt) {
            return '<i class="fa fa-undo"></i> ' + dt.i18n('buttons.reset', Botble.languages.tables.reset);
        },

        action: function (e, dt) {
            $('#dataTableBuilder thead input').val('').change();
            $('#dataTableBuilder thead select').val('').change();
            dt.search('').draw();
        }
    };

    DataTable.ext.buttons.reload = {
        className: 'buttons-reload',

        text: function (dt) {
            return '<i class="fa fa-refresh"></i> ' + dt.i18n('buttons.reload', Botble.languages.tables.reload);
        },

        action: function (e, dt) {

            dt.draw(false);
        }
    };

    $(document).ready(function () {
        if (typeof window.LaravelDataTables !== 'undefined') {
            $(document).on('click', '.deleteDialog', function (event) {
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
                            window.LaravelDataTables['dataTableBuilder'].row($('a[data-section="' + deleteURL + '"]').closest('tr')).remove().draw();
                            Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                        }
                    },
                    error: function (data) {
                        Botble.handleError(data);
                    }
                });
            });

            $(document).on('click', '.action-item', function (event) {
                event.preventDefault();
                var action_item = $(this);
                var span = $(this).find('span');
                console.log(span);
                if (span.length > 1) {
                    span = span.find('span');
                }
                var action = span.data('action');
                var url = span.data('href');
                if (action === 'create') {
                    window.location.href = url;
                } else if (action === 'delete') {
                    $('#delete-many-entry').data('href', url);
                    $('#delete-many-modal').modal('show');
                } else if (action === 'add-supper' || action === 'invite') {

                } else {
                    var ids = [];
                    $('.checkboxes:checked').each(function (i) {
                        ids[i] = $(this).val();
                    });

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {'ids': ids},
                        success: function (data) {
                            if (data.error) {
                                Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                            } else {
                                $.each(ids, function (index, item) {
                                    $(document).find('.group-checkable').prop('checked', false);
                                    $.uniform.update($(document).find('.group-checkable'));
                                    var _self = $('.checkboxes[value="' + item + '"]');
                                    _self.prop('checked', false);
                                    $.uniform.update(_self);
                                    var danger = 'label-danger';
                                    var success = 'label-success';

                                    if (data.status == 1) {
                                        _self.closest('tr').find('td > span.status-label').removeClass(danger).addClass(success).text(action_item.text());
                                    } else {
                                        _self.closest('tr').find('td > span.status-label').removeClass(success).addClass(danger).text(action_item.text());
                                    }
                                });
                                Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                            }
                        },
                        error: function (data) {
                            Botble.handleError(data);
                        }
                    });
                }

                $(this).closest('.dropdown-menu').hide();

            });

            $('#delete-many-entry').on('click', function (event) {
                event.preventDefault();
                $('#delete-many-modal').modal('hide');

                var ids = [];
                $('.checkboxes:checked').each(function (i) {
                    ids[i] = $(this).val();
                });

                $.ajax({
                    url: $(this).data('href'),
                    type: 'POST',
                    data: {'ids': ids},
                    success: function (data) {
                        if (data.error) {
                            Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                        } else {
                            $(document).find('.group-checkable').prop('checked', false);
                            $.uniform.update($(document).find('.group-checkable'));
                            $.each(ids, function (index, item) {
                                window.LaravelDataTables['dataTableBuilder'].row($('.checkboxes[value="' + item + '"]').closest('tr')).remove().draw();
                            });
                            Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                        }
                    },
                    error: function (data) {
                        Botble.handleError(data);
                    }
                });
            });

            $(document).find('.dataTables_filter input[type=search]').prop('placeholder', Botble.languages.tables.filter.trim());

            $(document).find('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 70
            }).removeClass('form-control');

            if (window.LaravelDataTables['dataTableBuilder']) {
                window.LaravelDataTables['dataTableBuilder'].on('draw.dt', function () {
                    $('.tip').tooltip({placement: 'top'});
                    if ($.fn.editable) {
                        $('.editable').editable();
                    }
                });
            }

            $('.group-checkable').uniform();

            $(document).on('change', '.group-checkable', function () {
                var set = $(this).attr('data-set');
                var checked = $(this).prop('checked');
                $(set).each(function () {
                    if (checked) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
                $.uniform.update(set);
                $(this).uniform();
            });

            $(document).on('click', ' tbody td .row-details', function () {
                var nTr = $(this).parents('tr')[0];
                if (window.LaravelDataTables['dataTableBuilder'].fnIsOpen(nTr)) {
                    $(this).addClass('row-details-close').removeClass('row-details-open');
                    window.LaravelDataTables['dataTableBuilder'].fnClose(nTr);
                } else {
                    $(this).addClass('row-details-open').removeClass('row-details-close');
                    window.LaravelDataTables['dataTableBuilder'].fnOpen(nTr, null, 'details');
                }
            });

            $(document).on('click', '.btn-reset-table', function (e) {
                e.preventDefault();
                $('#dataTableBuilder thead input').val('').change();
                $('#dataTableBuilder thead select').val('').change();
                window.LaravelDataTables['dataTableBuilder'].search('').draw();
            });
        }

    });

})(jQuery, jQuery.fn.dataTable);

}((this.LaravelElixirBundle = this.LaravelElixirBundle || {})));
//# sourceMappingURL=datatables.js.map
