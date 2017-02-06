<?php
/***** ツイートの検索を行う *****/
function searchTweets($keyword, $count, $next = false){
    require_once(__DIR__.'/../helper/requestContents.php');

	$request_url = 'https://api.twitter.com/1.1/search/tweets.json' ;
	$request_method = 'GET' ;

	// パラメータ
    if($next == false){
        $params_a = array(
            'q' => $keyword ,
            'count' => $count ,
        );
    }else{
        $params_a = array(
            'q' => $keyword ,
            'count' => $count ,
            'since_id' => $next , 
        );
    }
    
    $result = request($request_url,$params_a,$request_method);
    return $result;
}