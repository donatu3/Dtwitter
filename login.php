<?php
/***** アクセストークン取得 *****/
require_once(__DIR__.'/commons/helper/requestContents.php');
session_start();
if(isset( $_GET['oauth_token'] ) && !empty( $_GET['oauth_token'] ) && isset( $_GET['oauth_verifier'] ) && !empty( $_GET['oauth_verifier'] )){
    /*** 認証から戻ってきたとき ***/
    $request_url = 'https://api.twitter.com/oauth/access_token';
    $param_a = array('oauth_token' => $_GET['oauth_token'],'oauth_verifier' => $_GET['oauth_verifier']);
    $response = requestToken($request_url,$param_a);
    if( !isset( $response ) || empty( $response ) ){
        require(__DIR__.'/commons/helper/deleteSession.php');
        die("リクエスト失敗");
    }else{
        $query = parseResponse($response);
        $_SESSION['user_id'] = $query['user_id'];
        $_SESSION['screen_name'] = $query['screen_name'];
        $_SESSION['access_token'] = $query['oauth_token'];
        $_SESSION['access_token_secret'] = $query['oauth_token_secret'];
        $status = "complete";
    }
}else if(isset( $_GET['denied'] ) && !empty( $_GET['denied'])){
    /*** 「キャンセル」をクリックされたとき ***/
    $status = "cancel";
    require(__DIR__.'/commons/helper/deleteSession.php');
}else{
    /*** 初めての場合リクエストトークンの取得 ***/
    $status = "first";
    $request_url = 'https://api.twitter.com/oauth/request_token';
    $callback_url = (!isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS']) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $param_a = array('oauth_callback' => $callback_url);
    $response = requestToken($request_url,$param_a);
    if( !isset( $response ) || empty( $response ) ){
        $error = 'リクエスト失敗';
    }else{
        $query = parseResponse($response);
        if( !isset( $query['oauth_token'] ) || !isset( $query['oauth_token_secret'] ) ){
            require(__DIR__.'/commons/helper/deleteSession.php');
            die("リクエストトークン取得エラー");
        }else{
            $_SESSION['oauth_token_secret'] = $query['oauth_token_secret'];
            // ユーザーを認証画面へ飛ばす
            header( 'Location: https://api.twitter.com/oauth/authorize?oauth_token=' . $query['oauth_token'] );
            exit;
        }
    }
}

if($status == "complete"){
    //完了したらログイン情報を格納してホームへ移動
    require_once(__DIR__.'/commons/api/showUser.php');
    $friends = showUser($_SESSION['user_id']);
    if($friends['error']['flag'] == false){
        $_SESSION['user_icon'] = $friends['datas']['profile_image_url'];
        $_SESSION['name'] = $friends['datas']['name'];
    }else{
        $_SESSION['user_icon'] = false;
    }
}
header('Location: http://donatu33.sakura.ne.jp/Dtwitter/home.php');

//認証時に得られたデータを配列に格納する
function parseResponse($response){
    // 文字列を[&]で区切る
    $parameters = explode( '&' , $response );
    // エラー判定
    if( !isset( $parameters[1] ) || empty( $parameters[1] ) ){
        require(__DIR__.'/commons/helper/deleteSession.php');
        die("トークン取得エラー");
    }else{
        // それぞれの値を格納する配列
        $query = array();
        // [$parameters]をループ処理
        foreach( $parameters as $parameter ){
            // 文字列を[=]で区切る
            $pair = explode( '=' , $parameter );
            // 配列に格納する
            if( isset($pair[1]) ){
                $query[ $pair[0] ] = $pair[1];	
            }	
        }        
    }
    return $query;
}
?>
