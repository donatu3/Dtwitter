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
    <script src="./scripts/check_keyword.js"></script>
</head>
<body>
	<div id="header"></div>
    <?php 
        $current = 'search';
        require_once(__DIR__.'/commons/template/mainmenu.php');
        require_once(__DIR__.'/commons/template/status.php');
    ?>
    <?php if($login_status == true): ?>
        <div class="content">
        <?php 
                if(isset($_GET['keyword'])){
                    $keyword = htmlspecialchars($_GET['keyword'], ENT_NOQUOTES);
                    echo '<div class="flex">';
                    require_once(__DIR__.'/commons/response/search.php'); 
                    echo '</div>';
                }else{
        ?>
                <form action="search.php" method="get" onSubmit="return check()">
                    キーワード: <input id="keyword" type="text" name="keyword">
                    <button class="button" type="submit">検索する</button>
                </form>
        <?php
                }

        ?>
        </div>
    <?php endif; ?>
</body>