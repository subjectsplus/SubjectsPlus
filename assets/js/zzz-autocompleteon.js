(function(jQuery) {
    jQuery.fn.scrollTo = function() {
        jQuery('html, body').animate({
            scrollTop: $(this).offset().top + 'px'
        }, 'fast');
        return this; 
    }
})(jQuery);


