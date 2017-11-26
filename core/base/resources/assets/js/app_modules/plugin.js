$(document).ready(function () {
    $('#plugin-list').on('click', '.change_plugin_status', function (event) {
        event.preventDefault();
        var _self = $(this);
        if (_self.data('status') == 0) {
            _self.text('Activating...');
        } else {
            _self.text('Deactivating...');
        }
        $.ajax({
            url: Botble.routes.change_plugin_status + '?alias=' + _self.data('plugin'),
            type: 'GET',
            success: function (data) {
                if (data.error) {
                    Botble.showNotice('error', data.message, Botble.languages.notices_msg.error);
                } else {
                    if (_self.data('status') == 1) {
                        _self.data('status', 0).text('Activate');
                    } else {
                        _self.data('status', 1).text('Deactivate');
                    }
                    Botble.showNotice('success', data.message, Botble.languages.notices_msg.success);
                    window.location.reload();
                }
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });
});