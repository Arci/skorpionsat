$(document).ready(function()
{
    var numberOfImages = 0;
    var current = 1;
    var albumPreview = $('#album-preview');
    var albumImage = $('#album-image');
    var albumSlider = $('.album-slider');
    var buttonLeft = $("#button-left");
    var buttonRight = $("#button-right")
    var width_box = albumSlider.width();
    var height_box = albumSlider.height();
    var rightVisible = true;
    var leftVisible = false;
    
    albumSlider.each(function(){
        $(this).css("left",(width_box*numberOfImages));
        numberOfImages++;
    });
    
    $('.info').each(function(){
        console.log("id: " + $(this)[0].id);
        if($(this)[0].id != 1){
            $(this).css({"display" : "none"});
        }
    });
    
    $("#albumImage img").load(function(){
        var offset_y = ($(this).height()/10 - height_box/10)/2;
        $(this).css({"position" : "relative", "top" : -offset_y+"em"});
    });
    
    albumPreview.css("width", width_box);
    albumPreview.css("height", height_box);
    albumImage.css("width", width_box*numberOfImages);
    albumImage.css("height", height_box);
    buttonLeft.css({"opacity": "0" });
    buttonLeft.css({"cursor": "default" });
    if(numberOfImages < 2){
          buttonRight.css({"opacity": "0" });
          buttonRight.css({"cursor": "default" });      
    }
    
    //TODO rimuovere stampe su console
    console.log("@load - numberOfImages: " + numberOfImages + ", current: " + current);

    buttonRight.click(function(){
        if(!leftVisible){
            buttonLeft.animate({"opacity": "0.8" },1000);
            buttonLeft.css({"cursor": "pointer" });
        }
        if(current != numberOfImages){
            console.log("right pressed - current: " + current + ", go to: " + (current+1));
            if(current == numberOfImages-1){
                console.log("right pressed - current: " + current + ", next is last, animate");   
                $(this).animate({"opacity": "0" },1000);
                $(this).css({"cursor": "default" });
                rightVisible = false;
            }
            console.log("hide description of: " + current);
            $("#"+current).fadeOut("slow");
            current ++;
            albumSlider.each(function(){
                $(this).animate({"left":"-="+width_box+"px"}, 1000);
            });
            console.log("show description of: " + current);
            $("#"+current).fadeIn("slow");
        } else {
            console.log("right pressed - current: " + current + ", return");
            return;
        }
    });  
    
    buttonLeft.click(function(){
        if(!rightVisible){
            buttonRight.animate({"opacity": "0.8" }, 1000);
            buttonRight.css({"cursor": "pointer" });
        }
        if(current != 1){
            console.log("left pressed - current: " + current + ", go to: " + (current-1));
            if(current == 2){
                console.log("left pressed - current: " + current + ", next is last, animate");   
                $(this).animate({"opacity": "0" },1000);
                $(this).css({"cursor": "default" });
                leftVisible = false;
            }
            console.log("hide description of: " + current);
            $("#"+current).fadeOut("slow");
            current--;
            albumSlider.each(function(){
                $(this).animate({"left":"+="+width_box+"px"}, 1000);
            });
            console.log("show description of: " + current);
            $("#"+current).fadeIn("slow");
        } else {
            console.log("left pressed - current: " + current + ", return");
            return;
        }
    });
});