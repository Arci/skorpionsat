$(document).ready(function()
{
    var mask = $("#mask");
    var mainContainer = $("#main-container");
    if (mask.css("display") != "none") {
        $(".slide img").each(function(){
            $(this).load(function(){
                var offset = ($(this).height()/10 - mainContainer.height()/10)/2;
                $(this).css({"position" : "relative", "top" : -offset+"em"});
            });
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
    }
});