$(document).ready(function() {

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#backToTopBtn').fadeIn();
        } else {
            $('#backToTopBtn').fadeOut();
        }
    });


    $('#backToTopBtn').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        return false;
    });
});
