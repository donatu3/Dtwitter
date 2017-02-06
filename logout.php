<?php
    require_once(__DIR__.'/commons/helper/deleteSession.php');
?>
<head>
    <meta charset="UTF-8">
    <title>ログアウト</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/click.js"></script>
</head>
<body>
	<div id="header"></div>
    <?php 
        $current = 'none';
        require_once(__DIR__.'/commons/template/mainmenu.php');
    ?>
    <div class="content">
        <p>ログアウトしました。</p>
    </div>
</body>