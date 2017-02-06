<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="./styles/style.css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./scripts/click.js"></script>
</head>
<body>
	<div id="header"></div>
    <?php 
        $current = 'home';
        require_once(__DIR__.'/commons/template/mainmenu.php');
        require_once(__DIR__.'/commons/template/status.php');
    ?>
    <div class="content">
        one-way Checkerでは一方的にフォローしている、またはフォローされているユーザをチェックすることができます。
        <p class="home_list">片思い：自分が一方的にフォローしているユーザーを検索します。</p>
        <p class="home_list">片思われ：自分が一方的にフォローされているユーザーを検索します。</p>
        <p class="home_list">ツイート検索：キーワードからツイートの検索を行います。</p>
        <p class="home_list">API状況：検索の回数には限りがあるため、その残りの状況を表示します。</p>
        <p class="home_list">ログイン：このアプリを利用するためにtwitterアカウントでログインします（非ログイン時のみ選択可能です）。</p>
        <p class="home_list">ログアウト：他のユーザーに切り替えたい時などに使用します（ログイン時のみ選択可能です）。</p>
    </div>
</body>