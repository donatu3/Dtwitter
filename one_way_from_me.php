<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <title>片思い</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/follow.js"></script>
    <script src="./scripts/click.js"></script>
    <script src="./scripts/more_owfm.js"></script>
</head>
<body>
	<div id="header"></div>
    <?php 
        $current = 'from_me';
        require_once(__DIR__.'/commons/template/mainmenu.php');
        require_once(__DIR__.'/commons/template/status.php');
    ?>
    <?php if($login_status == true): ?>
        <div class="content">
            <div class="flex">
                <?php require_once(__DIR__.'/commons/response/one-way-from-me.php'); ?>
            </div>
        </div>
    <?php endif; ?>
</body>