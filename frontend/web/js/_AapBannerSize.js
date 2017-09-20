/** 跟随浏览器大小而变化 */    
$(window).resize(function(){
    /** 设置 banner-background样式类 的margin */
    var marginLeft = parseInt($('.k12 .container').css('marginLeft')) + 15;
    var marginRight = parseInt($('.k12 .container').css('marginRight')) + 15;
    $(".banner .banner-background").css({marginLeft:'-'+marginLeft+'px', marginRight:'-'+marginRight+'px'});
});
/** 设置 banner-background样式类 的margin */
$(window).bind("load", function(){
    var marginLeft = parseInt($('.k12 .container').css('marginLeft')) + 15;
    var marginRight = parseInt($('.k12 .container').css('marginRight')) + 15;
    $(".banner .banner-background").css({marginLeft:'-'+marginLeft+'px', marginRight:'-'+marginRight+'px'});
});