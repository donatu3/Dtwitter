$(function () {
    //フォローする
    $(document).on("click",".follow", function () {
        var self=this;
        var id = $(this).data("id");
        $("button[data-id='"+id+"']").each(function(i, elem) {
            $(elem).attr('disabled','true').text("処理中..");
        });
        $.ajax({ 
            data: { user_id : $(this).data("id")},
            dataType:"json", 
            success:function (result, textStatus) {
                if(result.error.flag == true){
                    alert("エラー："+result.error.message);
                    $("button[data-id='"+$(self).data("id")+"']").each(function(i, elem) {
                        $(elem).removeAttr('disabled');
                    });
                }else if($(self).data("lock") == 1){
                    $("button[data-id='"+$(self).data("id")+"']").each(function(i, elem) {
                        $(elem).removeAttr('disabled');
                        $(elem).text("承認待ち");
                        $(elem).attr("class","wait button");
                    });
                }else{
                    $("button[data-id='"+$(self).data("id")+"']").each(function(i, elem) {
                        $(elem).removeAttr('disabled');
                        $(elem).text("フォロー解除");
                        $(elem).attr("class","destroy button");
                    });

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
        var id = $(this).data("id");
        $("button[data-id='"+id+"']").each(function(i, elem) {
            $(elem).attr('disabled','true').text("処理中..");;
        });
        var self=this;
        $(this).attr('disabled','true').text("処理中..");
        $.ajax({ 
            data: { user_id : $(this).data("id")},
            dataType:"json", 
            success:function (result, textStatus) {
                if(result.error.flag == true){
                    alert("エラー："+result.error.message);
                    $("button[data-id='"+$(self).data("id")+"']").each(function(i, elem) {
                        $(elem).removeAttr('disabled');
                    });
                }else{
                    $("button[data-id='"+$(self).data("id")+"']").each(function(i, elem) {
                        $(elem).removeAttr('disabled');
                        $(elem).text("フォロー");
                        $(elem).attr("class","follow button");
                    });
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
