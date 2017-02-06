$(function () {
    //nextがあれば（非ログイン時やエラー時は無い）
    function setMoreButton(){
        if($("#next").length){
            var next_cursor = $("#next").text(); 
            if(next_cursor == 0){
                //$("div.content").append("<div class=\"more_wrap\">片思いのユーザーを全て表示しました</div>");
                $("<div class=\"more_wrap\">片思いのユーザーを全て表示しました</div>").appendTo("div.content").hide().fadeIn('slow');
            }else{
                //$("div.content").append("<div class=\"more_wrap\"><button id=\"more\" class=\"button\">さらに読み込む</button></div>");
                $("<div class=\"more_wrap\"><button id=\"more\" class=\"button\">さらに読み込む</button></div>").appendTo("div.content").hide().fadeIn('slow');

            }
        }
    }
    setMoreButton();
    //更に読み込む
    $(document).on("click","#more", function () {
        var self=this;
        $(this).attr('disabled','true').text("処理中..");
        $.ajax({ 
            data: { next_cursor : $("#next").text()},
            dataType:"html", 
            success:function (result, textStatus) {
                //前の番号なので消す
                $("#next").remove();
                //ボタンも消す
                $(".more_wrap").remove();
                //結果を追加する
                //$("div.flex").append(result);
                $(result).appendTo("div.flex").hide().fadeIn('slow');
                $("#next").hide();
                //ボタン再設置
                setMoreButton();
            },
            error: function(xhr, textStatus, errorThrown){
                alert("エラー" + textStatus + xhr + errorThrown);
                $(self).removeAttr('disabled').text("さらに読み込む");
            },
            type:"get",
            url:"\/Dtwitter\/commons\/response\/one-way-from-me.php"
        });
        return false;
    });
});
