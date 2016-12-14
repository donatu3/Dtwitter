<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
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
		<li class="current_menu"><a href="home.php">ホーム</a></li>
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
    <div id="content">
        one-way Checkerでは一方的にフォローしている、またはフォローされているユーザをチェックすることができます。
        <p class="home_list">片思い：自分が一方的にフォローしているユーザーを検索します。</p>
        <p class="home_list">片思われ：自分が一方的にフォローされているユーザーを検索します。</p>
        <p class="home_list">ツイート検索：キーワードからツイートの検索を行います。</p>
        <p class="home_list">API状況：検索の回数には限りがあるため、その残りの状況を表示します。</p>
        <p class="home_list">ログイン：このアプリを利用するためにtwitterアカウントでログインします（非ログイン時のみ選択可能です）。</p>
        <p class="home_list">ログアウト：他のユーザーに切り替えたい時などに使用します（ログイン時のみ選択可能です）。</p>
        <?php
            if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])){
                echo "調べたい内容を上部のメニューから選択してください。";
            }else{
                echo "アプリを利用するためには、まずは上部のメニューからログインをしてください。";
            }
        ?>
    </div>
</body>
</html>