$(document).ready(function () {
    var table_backup = $('#table-backups');
    table_backup.on('click', '.deleteDialog', function (event) {
        event.preventDefault();

        $('#delete-crud-entry').data('section', $(this).data('section'));
        $('#delete-crud-modal').modal('show');
    });

    table_backup.on('click', '.restoreBackup', function (event) {
        event.preventDefault();
        $('#restore-backup-button').data('section', $(this).data('section'));
        $('#restore-backup-modal').modal('show');
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
                    table_backup.find('a[data-section="' + deleteURL + '"]').closest('tr').remove();
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                }
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });

    $('#restore-backup-button').on('click', function (event) {
        event.preventDefault();
        var _self = $(this);
        _self.html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> ' + _self.text());

        $.ajax({
            url: _self.data('section'),
            type: 'GET',
            success: function (data) {
                _self.find('i').remove();
                _self.closest('.modal').modal('hide');

                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                    window.location.reload();
                }
            },
            error: function (data) {
                _self.find('i').remove();
                Botble.handleError(data);
            }
        });
    });

    $(document).on('click', '#generate_backup', function (event) {
        event.preventDefault();
        $('#name').val('');
        $('#description').val('');
        $('#create-backup-modal').modal('show');
    });

    $('#create-backup-modal').on('click', '#create-backup-button', function (event) {
        event.preventDefault();
        var _self = $(this);
        _self.html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> ' + _self.text());

        var name = $('#name').val();
        var description = $('#description').val();
        var error = false;
        if (name === '' || name === null) {
            error = true;
            Botble.showNotice('error', 'Backup name is required!', Botble.languages.notices_msg.error);
        }
        if (description === '' || description === null) {
            error = true;
            Botble.showNotice('error', 'Backup description is required!', Botble.languages.notices_msg.error);
        }

        if (!error) {
            $.ajax({
                url: $('div[data-route-create]').data('route-create'),
                type: 'POST',
                data: {
                    name: name,
                    description: description
                },
                success: function (data) {
                    _self.find('i').remove();
                    _self.closest('.modal').modal('hide');

                    if (data.error) {
                        Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                    } else {
                        table_backup.find('tbody').append(data.data);
                        Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                    }
                },
                error: function (data) {
                    _self.find('i').remove();
                    Botble.handleError(data);
                }
            });
        } else {
            _self.find('i').remove();
        }
    });
});