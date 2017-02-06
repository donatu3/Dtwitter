function check(){
    if($("#keyword").val() == ""){
        alert("検索ワードを入力してください");
        return false;
    }else{
        return true;
    }
}