<?php
    if(!isset($_SESSION)){
        session_start();
    }

    require_once(__DIR__.'/../config/parameters.php');
    require_once(__DIR__.'/../helper/enableHyperLink.php');
    require_once(__DIR__.'/../helper/path_to_url.php');
    require_once(__DIR__.'/../api/getFollowers.php');
    //自分のidからフォロワーを検索
    if(isset($_GET['next_cursor'])){
        $next_cursor = $_GET['next_cursor'];
        $friends = getFollowers($_SESSION['user_id'],$GET_FOLLOWER_NUM,$next_cursor);
    }else{
        $friends = getFollowers($_SESSION['user_id'],$GET_FOLLOWER_NUM);
    }
    if($friends['error']['flag'] == false){
        $next_cursor = $friends['next_cursor'];
        foreach($friends['datas']['users'] as $key => $val){
            if($val['following'] == false){
                echo "<div class='userinfo'>";
                echo $val['name'];
                echo "<a href='https://twitter.com/".$val['screen_name']."' target='_blank'>＠".$val['screen_name']."</a>";
                if($val['protected'] == 1){
                    $lock = 1;
                    echo '<img src="'.path_to_url(dirname(__DIR__).'/../images/lock.png').'" alt="LOCK" width="32" height="32">';
                }else{
                    $lock = 0;
                }
                if(empty($val['profile_image_url'])){
                    echo '<div class="center"><span class="profile"><img alt="プロフィール画像未登録" width="50" height="50"></span>';
                }else{
                    echo '<div class="center"><span class="profile"><img src='.$val['profile_image_url'].' alt="'.$val['name'].'さんのプロフィール画像" width="50" height="50"></span>';
                }
                if(empty($val['profile_banner_url'])){
                    echo '<span class="banner"><img alt="背景画像未登録" width="150" height="50"></span></div>';
                }else{
                    echo '<span class="banner"><img src='.$val['profile_banner_url'].' alt="'.$val['name'].'さんの背景画像" width="150" height="50"></span></div>';
                }
                echo "<div class='discription'>".enableHyperLink($val['description'])."</div>";
                if($val['follow_request_sent'] == true){
                    echo "<div class='right'><button class='wait button' data-id='".$val['id']."' data-lock='$lock' data-screen='".$val['screen_name']."'>承認待ち</button></div>";
                }else{
                    echo "<div class='right'><button class='follow button' data-id='".$val['id']."' data-lock='$lock' data-screen='".$val['screen_name']."'>フォローする</button></div>";
                }
                echo "</div>";
            }
        }
        echo "<div id='next' style='display:none;'>".$next_cursor."</div>";
    }else{
        echo "<div class=\"error_message\">".$friends['error']['message']."</div>";
    }