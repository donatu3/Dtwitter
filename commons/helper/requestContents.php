<?php 
/***** twitterAPIを使うとき *****/
function request($url = null,$params = null,$method = 'get'){
	$request_url = $url;
	$request_method = $method;
    $params_a = $params;
    require(__DIR__.'/../config/const.php');
    if(!isset($_SESSION)){
        session_start();
    }
	$api_key = $MY_API_KEY;
	$api_secret = $MY_API_SECRET;
	$access_token = $_SESSION['access_token'];
	$access_token_secret = $_SESSION['access_token_secret'];
	$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret );
    //パラメータの作成
	$params_b = array(
		'oauth_token' => $access_token ,
		'oauth_consumer_key' => $api_key ,
		'oauth_signature_method' => 'HMAC-SHA1' ,
		'oauth_timestamp' => time() ,
		'oauth_nonce' => microtime() ,
		'oauth_version' => '1.0' ,
	);
    //パラメータを整理する
	$params_c = array_merge( $params_a , $params_b );
    $context = makeParameter($params_c,$signature_key,$request_method,$request_url);
	if($request_method === 'POST' && $params){
		$context['http']['content'] = http_build_query( $params );
	}else{
		$request_url .= '?' . http_build_query( $params );
    }
    //データを取得する
    $json = myCurl($request_url,$context);
	$obj = json_decode($json,true);
    //データのエラーをチェック
    $retval['error'] = array("flag" => false,"message" => "");
	if(!$obj ){
        $retval['error']['flag'] = true;
        $retval['error']['message'] = "データ取得エラー";
	}else if(isset($obj['errors'])){
        $retval['error']['flag'] = true;
        $retval['error']['message'] = $obj['errors']['0']['message'];
    }else{
        $retval['datas'] = $obj;
    }    
    return $retval;
}

/***** ログイン時のトークン取得用 *****/
function requestToken($url = null,$params = null){
	$request_url = $url;
	$request_method = 'POST';
    $params_a = $params;
    require(__DIR__.'/../config/const.php');
    if(!isset($_SESSION)){
        session_start();
    }
	$api_key = $MY_API_KEY;
	$api_secret = $MY_API_SECRET;
    if(isset($_SESSION['oauth_token_secret'])){
        $request_token_secret = $_SESSION['oauth_token_secret'];
        $signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $request_token_secret );
        $first = false;
    }else{
        $access_token_secret = '';
        $signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret );
        $first = true;
    }
    //パラメータの作成
	$params_b = array(
		'oauth_consumer_key' => $api_key ,
		'oauth_signature_method' => 'HMAC-SHA1' ,
		'oauth_timestamp' => time() ,
		'oauth_nonce' => microtime() ,
		'oauth_version' => '1.0' ,
	);
    //パラメータを整理する
    $params_c = array_merge( $params_a , $params_b );
	foreach( $params_c as $key => $value ){
		if( $key == 'oauth_callback' ){
            continue ;
		}
		$params_c[ $key ] = rawurlencode( $value );
	}
    $context = makeParameter($params_c,$signature_key,$request_method,$request_url);
	if($request_method === 'POST' && $params){
		$context['http']['content'] = http_build_query( $params );
	}else{
		$request_url .= '?' . http_build_query( $params );
    }
    $json = myCurl($request_url,$context);
    return $json;
}


/***** パラメータの整理 *****/
function makeParameter($params_c,$signature_key,$request_method,$request_url){
	ksort( $params_c );
	$request_params = http_build_query( $params_c , '' , '&' );
	$request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params );
	$request_params = rawurlencode( $request_params );
	$encoded_request_method = rawurlencode( $request_method );
	$encoded_request_url = rawurlencode( $request_url );
	$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;
	$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE );
	$signature = base64_encode( $hash );
	$params_c['oauth_signature'] = $signature;
	$header_params = http_build_query( $params_c , '' , ',' );
	$context = array(
		'http' => array(
			'method' => $request_method , // リクエストメソッド
			'header' => array(			  // ヘッダー
				'Authorization: OAuth ' . $header_params ,
			) ,
		) ,
	);
    return $context;
}

/***** curlでデータを取得する *****/
function myCurl($request_url,$context){
	$curl = curl_init();
	curl_setopt( $curl , CURLOPT_URL , $request_url );
	curl_setopt( $curl , CURLOPT_HEADER, 1 );
	curl_setopt( $curl , CURLOPT_CUSTOMREQUEST , $context['http']['method'] );
	curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );	
	curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );
	curl_setopt( $curl , CURLOPT_HTTPHEADER , $context['http']['header'] );
	if(isset($context['http']['content']) && !empty($context['http']['content'])){
		curl_setopt( $curl , CURLOPT_POSTFIELDS , $context['http']['content'] );
	}
	curl_setopt( $curl , CURLOPT_TIMEOUT , 10);
	$res1 = curl_exec( $curl );
	$res2 = curl_getinfo( $curl );
    //curlにエラーがあれば内容を返して終了（タイムアウトなど）
    if(curl_errno($curl)){
        die(curl_error($curl));
    }
	curl_close( $curl );
	$json = substr( $res1, $res2['header_size'] );
    return $json;
}