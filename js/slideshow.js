$(document).ready(function()
{
    //var height = $(window).height()/10 - $("#main-container").offset().top/10 -14.5; //14.5 is magic number
    //$("#main-container").height(height+"em");
    $(".slide img").load(function(){
        var offset = ($(this).height()/10 - $("#main-container").height()/10)/2;
        $(this).css({"position" : "relative", "top" : -offset+"em"});
    });
    
    function autoSlide() {
        var nextidx = (parseInt($('#main-container .current').index() + 1) == $('#main-container .slide').length) ? 0 : parseInt($('#main-container .current').index() + 1);
        $('#main-container .current').fadeOut('slow', function() {
            $(this).removeClass('current');
            $('#main-container .slide').eq(nextidx).css({"display" : "none"});
            $('#main-container .slide').eq(nextidx).fadeIn('slow', function() {
                $(this).addClass('current');
            });
        });
    }
    
    setInterval(autoSlide, 5000);
});