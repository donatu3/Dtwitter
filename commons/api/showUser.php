<?php
/***** ユーザー情報を1つだけ取得する *****/
function showUser($user_id){
    require_once(__DIR__.'/../helper/requestContents.php');
    
	$request_url = 'https://api.twitter.com/1.1/users/show.json';
	$request_method = 'GET';

	$params_a = array(
		'user_id' => $user_id ,
	);
    
    $result = request($request_url,$params_a,$request_method);

    return $result;
}