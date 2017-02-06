<?php 
/***** APIの使用状況を取得する *****/
function getRateLimits($resources = array()){
    require_once(__DIR__.'/../helper/requestContents.php');
    
	$request_url = 'https://api.twitter.com/1.1/application/rate_limit_status.json';
	$request_method = 'GET';
    
	// パラメータ
    if(empty($resources)){
        $params_a = array();
    }else{
        $params_a = array(
    		'resources' => implode(",", $resources),
        ) ;
    }
    
    $result = request($request_url,$params_a,$request_method);
    return $result;
}