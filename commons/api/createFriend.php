<?php
/***** フォローする *****/
    require_once(__DIR__.'/../helper/requestContents.php');

	$request_url = 'https://api.twitter.com/1.1/friendships/create.json';
	$request_method = 'POST';

	// パラメータ
	$params_a = array(
		'user_id' => $_GET['user_id'] ,
	);

    $result = request($request_url,$params_a,$request_method);
    echo json_encode($result);