$(function () {
    //フォローする
    $(document).on("click",".follow", function () {
        var self=this;
        $(this).attr('disabled','true').text("処理中..");
        $.ajax({ 
            data: { user_id : $(this).data("id")},
            dataType:"json", 
            success:function (result, textStatus) {
                if(result.error.flag == true){
                    alert("エラー："+result.error.message);
                    $(self).removeAttr('disabled');
                }else if($(self).data("lock") == 1){
                    $(self).removeAttr('disabled');
                    $(self).text("承認待ち");
                    $(self).attr("class","wait button");
                }else{
                    $(self).removeAttr('disabled');
                    $(self).text("フォロー解除");
                    $(self).attr("class","destroy button");
                }
            },
            error: function(xhr, textStatus, errorThrown){
                alert("エラー：もう一度試してください。" + textStatus + xhr + errorThrown);
            },
            type:"get",
            url:"\/Dtwitter\/commons\/createFriend.php"
        });
        return false;
    });
    //フォローを外す
    $(document).on("click",".destroy", function () {
        var self=this;
        $(this).attr('disabled','true').text("処理中..");
        $.ajax({ 
            data: { user_id : $(this).data("id")},
            dataType:"json", 
            success:function (result, textStatus) {
                if(result.error.flag == true){
                    alert("エラー："+result.error.message);
                    $(self).removeAttr('disabled');
                }else{
                    $(self).removeAttr('disabled');
                    $(self).text("フォロー");
                    $(self).attr("class","follow button");
                }
            },
            error: function(xhr, textStatus, errorThrown){
                alert("エラー：もう一度試してください。" + textStatus + xhr + errorThrown);
            },
            type:"get",
            url:"\/Dtwitter\/commons\/destroyFriend.php"
        });
        return false;
    });
    //承認待ちキャンセル誘導
    $(document).on("click",".wait", function () {
        if(confirm("APIではリクエストをキャンセルできません。\n公式サイトからキャンセルしてください。\n公式サイトを開きますか？")){
            window.open('https://twitter.com/'+$(this).data("screen"), '_blank');
        }
        return false;
    });  
});
