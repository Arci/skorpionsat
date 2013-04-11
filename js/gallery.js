$(document).ready(function()
{
    var numberOfImages = 0;
    var current = 1;
    var width_box = $('.album-slider').width();
    var height_box = $('.album-slider').height();
    var rightVisible = true;
    var leftVisible = false;
    
    $('.album-slider').each(function(){
        $(this).css("left",(width_box*numberOfImages));
        
        numberOfImages++;
    });
    
    $('.info').each(function(){
        console.log("id: " + $(this)[0].id);
        if($(this)[0].id != 1){
            $(this).css({"display" : "none"});
        }
    });
    
    $('#album-preview').css("width", width_box);
    $('#album-preview').css("height", height_box);
    $('#album-image').css("width", width_box*numberOfImages);
    $('#album-image').css("height", height_box);
    $("#button-left").css({"opacity": "0" });
    $("#button-left").css({"cursor": "default" });
    
    //TODO rimuovere stampe su console
    console.log("@load - numberOfImages: " + numberOfImages + ", current: " + current);

    $("#button-right").click(function(){
        if(!leftVisible){
            $("#button-left").animate({"opacity": "0.8" },1000);
            $("#button-left").css({"cursor": "pointer" });
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
            $('.album-slider').each(function(){
                $(this).animate({"left":"-="+width_box+"px"}, 1000);
            });
            console.log("show description of: " + current);
            $("#"+current).fadeIn("slow");
        } else {
            console.log("right pressed - current: " + current + ", return");
            return;
        }
    });  
    
    $("#button-left").click(function(){
        if(!rightVisible){
            $("#button-right").animate({"opacity": "0.8" }, 1000);
            $("#button-right").css({"cursor": "pointer" });
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
            $('.album-slider').each(function(){
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