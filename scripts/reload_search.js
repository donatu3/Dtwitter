$(function () {
    function reload(){
        if($("#next").length && $("#keyword").length){
            $.ajax({ 
                data: { next : $("#next").text(), keyword : $("#keyword").text()},
                dataType:"html", 
                success:function (result, textStatus) {
                    $("#next").remove();
                    $(result).prependTo("div.flex").hide().fadeIn('slow');
                    $("#next").hide();
                },
                error: function(xhr, textStatus, errorThrown){
                    $("#next").remove();
                    //alert("エラー" + textStatus + xhr + errorThrown);
                },
                type:"get",
                url:"\/Dtwitter\/commons\/search.php"
            });
            setTimeout(reload,20000);
        }
    }
    setTimeout(reload,20000);
});