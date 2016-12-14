<?php
    /***** アクセストークン取得 *****/
    require_once(__DIR__.'/commons/const.php');
	$api_key = $MY_API_KEY;
	$api_secret = $MY_API_SECRET;
    $status = "default";
	//Callback URL
	$callback_url = (!isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS']) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    session_start();
	if(isset($_SESSION['access_token']) && isset($_SESSION['access_token_secret'])){
        /*** 既に取得済みの場合 ***/
        $status = "already";
    }else if(isset( $_GET['oauth_token'] ) && !empty( $_GET['oauth_token'] ) && isset( $_GET['oauth_verifier'] ) && !empty( $_GET['oauth_verifier'] )){
    	/*** 「連携アプリを認証」をクリックされたとき ***/
		// [リクエストトークン・シークレット]をセッションから呼び出す
		$request_token_secret = $_SESSION['oauth_token_secret'];
		// リクエストURL
		$request_url = 'https://api.twitter.com/oauth/access_token';
		// リクエストメソッド
		$request_method = 'POST';
		// キーを作成する
		$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $request_token_secret );
		// パラメータ([oauth_signature]を除く)を連想配列で指定
		$params = array(
			'oauth_consumer_key' => $api_key,
			'oauth_token' => $_GET['oauth_token'],
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp' => time(),
			'oauth_verifier' => $_GET['oauth_verifier'],
			'oauth_nonce' => microtime(),
			'oauth_version' => '1.0',
		);
		// 配列の各パラメータの値をURLエンコード
		foreach( $params as $key => $value ){
			$params[ $key ] = rawurlencode( $value );
		}
		// 連想配列をアルファベット順に並び替え
		ksort($params);
		// パラメータの連想配列を[キー=値&キー=値...]の文字列に変換
		$request_params = http_build_query( $params , '' , '&' );
		// 変換した文字列をURLエンコードする
		$request_params = rawurlencode($request_params);
		// リクエストメソッドをURLエンコードする
		$encoded_request_method = rawurlencode( $request_method );
		// リクエストURLをURLエンコードする
		$encoded_request_url = rawurlencode( $request_url );
		// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
		$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;
		// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
		$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE );
		// base64エンコードして、署名[$signature]が完成する
		$signature = base64_encode( $hash );
		// パラメータの連想配列、[$params]に、作成した署名を加える
		$params['oauth_signature'] = $signature;
		// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
		$header_params = http_build_query( $params , '' , ',' );
		// リクエスト用のコンテキストを作成する
		$context = array(
			'http' => array(
				'method' => $request_method, //リクエストメソッド
				'header' => array(			  //カスタムヘッダー
					'Authorization: OAuth ' . $header_params,
				),
			),
		);
		// cURLを使ってリクエスト
		$curl = curl_init();
		curl_setopt( $curl , CURLOPT_URL , $request_url );
		curl_setopt( $curl , CURLOPT_HEADER, 1 ) ; 
		curl_setopt( $curl , CURLOPT_CUSTOMREQUEST , $context['http']['method'] );			// メソッド
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );								// 証明書の検証を行わない
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );								// curl_execの結果を文字列で返す
		curl_setopt( $curl , CURLOPT_HTTPHEADER , $context['http']['header'] );			// ヘッダー
		curl_setopt( $curl , CURLOPT_TIMEOUT , 5 );										// タイムアウトの秒数
		$res1 = curl_exec( $curl );
		$res2 = curl_getinfo( $curl );
		curl_close( $curl );
		// 取得したデータ
		$response = substr($res1, $res2['header_size']);
		// リクエストが成功しなかった場合
		if( !isset( $response ) || empty( $response ) ){
			$error = 'リクエスト失敗';
		}else{
			// 文字列を[&]で区切る
			$parameters = explode( '&' , $response );
			// エラー判定
			if( !isset( $parameters[1] ) || empty( $parameters[1] ) ){
				//echo "アクセストークン取得エラー";
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
                // 各データの整理
                $access_token = $query['oauth_token'];		// アクセストークン
                $access_token_secret = $query['oauth_token_secret'];		// アクセストークン・シークレット
                $user_id = $query['user_id'];		// ユーザーID
                $screen_name = $query['screen_name'];		// スクリーンネーム
                //todoセッションに保存する
                $_SESSION['user_id'] = $user_id;
                $_SESSION['screen_name'] = $screen_name;
				$_SESSION['access_token'] = $access_token;
				$_SESSION['access_token_secret'] = $access_token_secret;
                $status = "complete";
			}
		}
    }elseif(isset( $_GET['denied'] ) && !empty( $_GET['denied'])){
	/*** 「キャンセル」をクリックされたとき ***/
		// エラーメッセージを出力して終了
        $status = "cancel";
    }else{
	/*** 初めての場合リクエストトークンの取得 ***/
		// [アクセストークンシークレット] (まだ存在しないので「なし」)
		$access_token_secret = '';
		// エンドポイントURL
		$request_url = 'https://api.twitter.com/oauth/request_token';
		// リクエストメソッド
		$request_method = 'POST';
		// キーを作成する (URLエンコードする)
		$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret );
		// パラメータ([oauth_signature]を除く)を連想配列で指定
		$params = array(
			'oauth_callback' => $callback_url ,
			'oauth_consumer_key' => $api_key ,
			'oauth_signature_method' => 'HMAC-SHA1' ,
			'oauth_timestamp' => time() ,
			'oauth_nonce' => microtime() ,
			'oauth_version' => '1.0' ,
		);
		// 各パラメータをURLエンコードする
		foreach( $params as $key => $value ){
			// コールバックURLはURLエンコードしない
			if( $key == 'oauth_callback' ){
				continue ;
			}
			// URLエンコード処理
			$params[ $key ] = rawurlencode( $value );
		}
		// 連想配列をアルファベット順に並び替える
		ksort( $params );
		// パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
		$request_params = http_build_query( $params , '' , '&' );
		// 変換した文字列をURLエンコードする
		$request_params = rawurlencode( $request_params );
		// リクエストメソッドをURLエンコードする
		$encoded_request_method = rawurlencode( $request_method );
		// リクエストURLをURLエンコードする
		$encoded_request_url = rawurlencode( $request_url );
		// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
		$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;
		// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
		$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE );
		// base64エンコードして、署名[$signature]が完成する
		$signature = base64_encode( $hash );
		// パラメータの連想配列、[$params]に、作成した署名を加える
		$params['oauth_signature'] = $signature;
		// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
		$header_params = http_build_query( $params , '' , ',' );
		// リクエスト用のコンテキストを作成する
		$context = array(
			'http' => array(
				'method' => $request_method , //リクエストメソッド
				'header' => array(			  //カスタムヘッダー
					'Authorization: OAuth ' . $header_params,
				),
			),
		);
		// cURLを使ってリクエスト
		$curl = curl_init();
		curl_setopt( $curl , CURLOPT_URL , $request_url );
		curl_setopt( $curl , CURLOPT_HEADER, 1 ); 
		curl_setopt( $curl , CURLOPT_CUSTOMREQUEST , $context['http']['method'] );			// メソッド
		curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );								// 証明書の検証を行わない
		curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );								// curl_execの結果を文字列で返す
		curl_setopt( $curl , CURLOPT_HTTPHEADER , $context['http']['header'] );			// ヘッダー
		curl_setopt( $curl , CURLOPT_TIMEOUT , 5 );										// タイムアウトの秒数
		$res1 = curl_exec( $curl );
		$res2 = curl_getinfo( $curl );
		curl_close( $curl );
		// 取得したデータ
		$response = substr( $res1, $res2['header_size'] );				// 取得したデータ(JSONなど)
		// リクエストが成功しなかった場合
		if( !isset( $response ) || empty( $response ) ){
			$error = 'リクエスト失敗';
		}else{
			// 文字列を[&]で区切る
			$parameters = explode( '&' , $response );
			// エラー判定
			if( !isset( $parameters[1] ) || empty( $parameters[1] ) ){
				$error_msg = true;
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
				// エラー判定
				if( !isset( $query['oauth_token'] ) || !isset( $query['oauth_token_secret'] ) ){
				    $status = "error";
				}else{
					/*** [手順2] ユーザーを認証画面に移動させる ***/
					// セッション[$_SESSION["oauth_token_secret"]]に[oauth_token_secret]を保存する
					$_SESSION['oauth_token_secret'] = $query['oauth_token_secret'];
					// ユーザーを認証画面へ飛ばす
					header( 'Location: https://api.twitter.com/oauth/authorize?oauth_token=' . $query['oauth_token'] );
					// 処理を終了
					exit;
				}
			}
		}
	}
    if($status == "complete"){
        require_once(__DIR__.'/commons/showUser.php');
        $friends = showUser($_SESSION['user_id']);
        if($friends['error']['flag'] == false){
            $_SESSION['user_icon'] = $friends['datas']->profile_image_url;
            $_SESSION['name'] = $friends['datas']->name;
        }else{
            $_SESSION['user_icon'] = false;
        }
    }
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/click.js"></script>
</head>
<body>
	<!-- ヘッダ -->
	<div id="header"></div>
	<!-- メインメニュー -->
	<ul id="menu">
		<li class="menu"><a href="home.php">ホーム</a></li>
		<li class="menu"><a href="one_way_from_me.php">片思い</a></li>
		<li class="menu"><a href="one_way_from_friend.php">片思われ</a></li>
        <li class="menu"><a href="search.php">ツイート検索</a></li>
		<li class="menu"><a href="rate_limits.php">API状況</a></li>
        <?php
            if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])){
        ?>
        <li class="menu"><a href="logout.php">ログアウト</a></li>    
        <?php
            }else{
        ?>      
        <li class="menu"><a href="login.php">ログイン</a></li>       
        <?php
            }
        ?>
	</ul>
    <div class=clear></div>
        <?php
            if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])){
        ?>
        <p class="login_info">ログインしています<br><?php echo $_SESSION['name']; ?><a href='https://twitter.com/<?php echo $_SESSION['screen_name'] ?>' target='_blank'>＠<?php echo $_SESSION['screen_name']; ?></a><img src='<?php echo $_SESSION['user_icon']; ?>' alt="プロフィール画像" width="48" height="48"></p>
        <?php
            }else{
        ?>      
        <p class="login_info">ログインしていません</p>       
        <?php
            }
        ?>
    <div id="home_contents">
        <?php
            if($status == "complete"){
                echo $_SESSION['name']."＠".$_SESSION['screen_name']."としてログインしています。別のユーザーに切り替える場合は一度ログアウトしてください。";
            }else if($status == "already"){
                echo "既にログイン済みです。別のユーザーに切り替える場合は一度ログアウトしてください。";
            }else if($status == "cancel"){
                echo "認証をキャンセルしました。アプリを利用する場合はログインをしてください。";
            }else{
                echo "エラーが発生しました。１５分以上した後もう一度お試しください。";
            }
        ?>
    </div>
</body>
</html>