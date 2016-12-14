<?php
/***** フォローを外す *****/
    //APIトークンなど
    require(__DIR__.'/const.php');
    if(!isset($_SESSION)){
        session_start();
    }
	$api_key = $MY_API_KEY;
	$api_secret = $MY_API_SECRET;
	$access_token = $_SESSION['access_token'];
	$access_token_secret = $_SESSION['access_token_secret'];
	$request_url = 'https://api.twitter.com/1.1/friendships/destroy.json' ;		// エンドポイント
	$request_method = 'POST' ;

	// パラメータA (オプション)
	$params_a = array(
		'user_id' => $_GET['user_id'] ,
	) ;

	$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret ) ;
	$params_b = array(
		'oauth_token' => $access_token ,
		'oauth_consumer_key' => $api_key ,
		'oauth_signature_method' => 'HMAC-SHA1' ,
		'oauth_timestamp' => time() ,
		'oauth_nonce' => microtime() ,
		'oauth_version' => '1.0' ,
	) ;
	$params_c = array_merge( $params_a , $params_b ) ;
	ksort( $params_c ) ;
	$request_params = http_build_query( $params_c , '' , '&' ) ;
	$request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params ) ;
	$request_params = rawurlencode( $request_params ) ;
	$encoded_request_method = rawurlencode( $request_method ) ;
	$encoded_request_url = rawurlencode( $request_url ) ;
	$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params ;
	$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE ) ;
	$signature = base64_encode( $hash ) ;
	$params_c['oauth_signature'] = $signature ;
	$header_params = http_build_query( $params_c , '' , ',' ) ;
	$context = array(
		'http' => array(
			'method' => $request_method , // リクエストメソッド
			'header' => array(			  // ヘッダー
				'Authorization: OAuth ' . $header_params ,
			) ,
		) ,
	) ;
	if( $params_a ){
		$context['http']['content'] = http_build_query( $params_a ) ;
	}
	$curl = curl_init() ;
	curl_setopt( $curl , CURLOPT_URL , $request_url ) ;
	curl_setopt( $curl , CURLOPT_HEADER, 1 ) ; 
	curl_setopt( $curl , CURLOPT_CUSTOMREQUEST , $context['http']['method'] ) ;			// メソッド
	curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false ) ;								// 証明書の検証を行わない
	curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true ) ;								// curl_execの結果を文字列で返す
	curl_setopt( $curl , CURLOPT_HTTPHEADER , $context['http']['header'] ) ;			// ヘッダー
	if( isset( $context['http']['content'] ) && !empty( $context['http']['content'] ) )
	{
		curl_setopt( $curl , CURLOPT_POSTFIELDS , $context['http']['content'] ) ;			// リクエストボディ
	}
	curl_setopt( $curl , CURLOPT_TIMEOUT , 5 ) ;										// タイムアウトの秒数
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;
	curl_close( $curl ) ;

	// 取得したデータ
	$json = substr( $res1, $res2['header_size'] ) ;				// 取得したデータ(JSONなど)
	$obj = json_decode( $json ) ; 
    
    // エラー判定
    $retval['error'] = array("flag" => false,"message" => "","zero" => false);
	if( !$json || !$obj ){
        $retval['error']['flag'] = true;
        $retval['error']['message'] = "データ取得エラー";
	}
    if(isset($obj->errors)){
        $retval['error']['flag'] = true;
        $retval['error']['message'] = $obj->errors[0]->message;
    }
    if($retval['error']['flag'] == false){
        $retval['datas'] = $obj;
    }
    echo json_encode($retval);