$(function () {

    $("body").click(function(e){
        //パラメータ
        var box = {
            "width":"100",
            "height":"100"
        };
        //クリックした座標
        var x = e.pageX;
        var y = e.pageY;
        //装飾 クリック地点をセットしておく
        var style = {
            "position":"absolute",
            "top":y,
            "left":x,
            "z-index":100,
            "border":"solid",
            "opacity": 0.5,
            //丸くする
            "border-radius":box.width/2
        };
        //適当な位置に追加
        $(this).append('<div class="circle"></div>');
        //見つけて、装飾して、動かして、消す
        $(this).find(".circle:last").css(style).animate({
            "width":box.width,
            "height":box.height,
            "top":(y - box.height/2),
            "left":(x - box.width/2),
        },{
            "duration": 500,
            "queue":false,
        }).fadeOut(500,function(){
            $(this).remove();
        });
    });

});
