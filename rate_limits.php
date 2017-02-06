<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <title>API状況</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/click.js"></script>
</head>
<body>
	<div id="header"></div>
    <?php 
        $current = 'limits';
        require_once(__DIR__.'/commons/template/mainmenu.php');
        require_once(__DIR__.'/commons/template/status.php');
    ?>
    <?php if($login_status == true): ?>
        <div class="content">
            <?php require_once(__DIR__.'/commons/response/rate-limits.php'); ?>
        </div>
    <?php endif; ?>
</body>