<ul id="menu">
    <?php if($current === 'home'): ?>
        <li class="current_menu"><a href="home.php">ホーム</a></li>
    <?php else: ?>
        <li class="menu"><a href="home.php">ホーム</a></li>
    <?php endif; ?>
    
    <?php if($current === 'from_me'): ?>
        <li class="current_menu"><a href="one_way_from_me.php">片思い</a></li>
    <?php else: ?>
        <li class="menu"><a href="one_way_from_me.php">片思い</a></li>
    <?php endif; ?>

    <?php if($current === 'from_friend'): ?>
	   <li class="current_menu"><a href="one_way_from_friend.php">片思われ</a></li>
    <?php else: ?>
	   <li class="menu"><a href="one_way_from_friend.php">片思われ</a></li>
    <?php endif; ?>
    
    <?php if($current === 'search'): ?>
        <li class="current_menu"><a href="search.php">ツイート検索</a></li>
    <?php else: ?>
        <li class="menu"><a href="search.php">ツイート検索</a></li>
    <?php endif; ?>

    <?php if($current === 'limits'): ?>
        <li class="current_menu"><a href="rate_limits.php">API状況</a></li>
    <?php else: ?>
        <li class="menu"><a href="rate_limits.php">API状況</a></li>
    <?php endif; ?>

    <?php if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])): ?>
        <li class="menu"><a href="logout.php">ログアウト</a></li>    
    <?php else: ?>  
        <li class="menu"><a href="login.php">ログイン</a></li>       
    <?php endif; ?>
</ul>
<div class=clear></div>