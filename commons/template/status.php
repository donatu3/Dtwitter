<?php 
    if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])){
        $login_status = true;
?>
        <p class="login_info">ログインしています<br>
            <?php echo $_SESSION['name']; ?>
            <a href='https://twitter.com/<?php echo $_SESSION['screen_name'] ?>' target='_blank'>＠<?php echo $_SESSION['screen_name']; ?></a>
            <img src='<?php echo $_SESSION['user_icon']; ?>' alt="プロフィール画像" width="48" height="48">
        </p>
<?php 
    }else{
        $login_status = false;
?>      
        <p class="login_info">ログインしていません</p>
        <p>ログインしてから利用してください。</p>
<?php
    }
?>