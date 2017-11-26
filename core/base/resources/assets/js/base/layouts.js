var Botble = Botble || {};

Botble.getViewPort = function () {
    var e = window,
        a = 'inner';
    if (!('innerWidth' in window)) {
        a = 'client';
        e = document.documentElement || document.body;
    }

    return {
        width: e[a + 'Width'],
        height: e[a + 'Height']
    };
};

Botble.getResponsiveBreakpoint = function (size) {
    // bootstrap responsive breakpoints
    var sizes = {
        'xs': 480,     // extra small
        'sm': 768,     // small
        'md': 992,     // medium
        'lg': 1200     // large
    };

    return sizes[size] ? sizes[size] : 0;
}

// Set proper height for sidebar and content. The content and sidebar height must be synced always.
var handleSidebarAndContentHeight = function () {
    var content = $('.page-content');
    var sidebar = $('.sidebar');
    var body = $('body');
    var height;


    var headerHeight = $('.navbar.navbar-inverse').outerHeight();
    var footerHeight = $('.page-footer').outerHeight();

    if (Botble.getViewPort().width < Botble.getResponsiveBreakpoint('md')) {
        height = Botble.getViewPort().height - headerHeight - footerHeight;
    } else {
        height = sidebar.height() + 20;
    }

    if ((height + headerHeight + footerHeight) <= Botble.getViewPort().height) {
        height = Botble.getViewPort().height - headerHeight - footerHeight;
    }

    content.css('min-height', height);
};

$(document).ready(function () {
    handleSidebarAndContentHeight();
});