<?php
/***** フォローしている人のIDを取得する *****/
function getFriendIds($user_id,$count_max,$cursor = NULL){
    require_once(__DIR__.'/../helper/requestContents.php');
    
	$request_url = 'https://api.twitter.com/1.1/friends/ids.json';
	$request_method = 'GET';

	// パラメータ
	$params_a = array(
		'user_id' => $user_id,
		'count' => $count_max ,
	);
    if($cursor != NULL){
        $params_a['cursor'] = (int)($cursor);
    }
    
    $result = request($request_url,$params_a,$request_method);

    if($result['error']['flag'] == false){
        $result['next_cursor'] = $result['datas']['next_cursor'];
    }
    return $result;
}