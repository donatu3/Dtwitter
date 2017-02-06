<?php
/***** フォロワーを取得する *****/
function getFollowers($user_id,$count_max,$cursor = NULL){
    require_once(__DIR__.'/../helper/requestContents.php');

	$request_url = 'https://api.twitter.com/1.1/followers/list.json';
	$request_method = 'GET';

	// パラメータ
    $params_a = array(
        'user_id' => $user_id ,
        'count' => $count_max ,
    );
    if($cursor != NULL){
        $params_a['cursor'] = (int)($cursor);
    }

    $result = request($request_url,$params_a,$request_method);

    // エラー判定
    if(empty($result['datas']['users'])){
        $result['error']['flag'] = true;
        $result['error']['message'] = "ユーザーがみつかりませんでした";
        $result['error']['zero'] = true;
    }
    if($result['error']['flag'] == false){
        $result['next_cursor'] = $result['datas']['next_cursor'];
    }
    return $result;
}