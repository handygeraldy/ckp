! function($) {
    "use strict";
    $("#sidebarToggle, #sidebarToggleTop").on("click", (function(e) { $("body").toggleClass("sidebar-toggled"), $(".sidebar").toggleClass("toggled"), $(".sidebar").hasClass("toggled") && $(".sidebar .collapse").collapse("hide") })), $(window).resize((function() { $(window).width() < 768 && $(".sidebar .collapse").collapse("hide") })), $("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", (function(e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += 30 * (delta < 0 ? 1 : -1), e.preventDefault()
        }
    }))
}(jQuery);