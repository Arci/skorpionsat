$(document).ready(function()
{
    function autoSlide() {
        var nextidx = (parseInt($('#main-container .current').index() + 1) == $('#main-container .slide').length) ? 0 : parseInt($('#main-container .current').index() + 1);
        $('#main-container .current').fadeOut('slow', function() {
            $(this).removeClass('current');
            $('#main-container .slide').eq(nextidx).fadeIn('slow', function() {
                $(this).addClass('current');
            });
        });
    }
    
    setInterval(autoSlide, 5500);
});