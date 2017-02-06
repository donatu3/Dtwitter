<?php
/***** IDからフォロー、フォロワー状態を取得する *****/
function getConnection($ids){
    require_once(__DIR__.'/../helper/requestContents.php');
    
	$request_url = 'https://api.twitter.com/1.1/friendships/lookup.json';
	$request_method = 'GET';

	// パラメータ
	$params_a = array(
		'user_id' => implode(",", $ids) ,
	);
    
    $result = request($request_url,$params_a,$request_method);

    // エラー判定
    if(empty($result)){
        $result['error']['flag'] = true;
        $result['error']['message'] = "ユーザーがみつかりませんでした";
        $result['error']['zero'] = true;
    }
    return $result;
}