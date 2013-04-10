$(document).ready(function()
{
    var numberOfImages = 5
    var x = 0;
    var width_box = $('.album-slider').width();
    var height_box = $('.album-slider').height();
    $('.album-slider').each(function()
    {
        $(this).css("left",(width_box*x));
        x++;
    });
    $('#album-preview').css("width", width_box);
    $('#album-preview').css("height", height_box);
    $('#album-image').css("width", width_box*x);
    $('#album-image').css("height", height_box);
    
    $("#button-right").click(function(){
        if(x > 1){
            x--;
            $('.album-slider').each(function(){
                $(this).animate({"left":"-="+width_box+"px"},1000);
            });
        }
    });
    
    $("#button-left").click(function(){
        if(x < numberOfImages){
            x++;
            $('.album-slider').each(function(){
                $(this).animate({"left":"+="+width_box+"px"},1000);
            });
        }
    });
});