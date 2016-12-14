<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <title>検索</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/follow_for_search.js"></script>
    <script src="./scripts/reload_search.js"></script>
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
        <li class="current_menu"><a href="search.php">ツイート検索</a></li>
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
        <div class="content">
        <?php 
                if(isset($_GET['keyword'])){
                    $keyword = htmlspecialchars($_GET['keyword'], ENT_NOQUOTES);
                    echo '<div class="flex">';
                    require_once(__DIR__.'/commons/search.php'); 
                    echo '</div>';
                }else{
        ?>
                <form action="search.php" method="get">
                    キーワード: <input type="text" name="keyword">
                    <button class="button" type="submit">検索する</button>
                </form>
        <?php
                }

        ?>
        </div>
        <?php
            }else{
        ?>      
        <p class="login_info">ログインしていません</p>  
        <p>ログインしてから利用してください。</p>
        <?php
            }
        ?>
</body>