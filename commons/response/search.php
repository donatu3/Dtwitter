<?php
    if(!isset($_SESSION)){
        session_start();
    }
    require_once(__DIR__.'/../config/parameters.php');
    require_once(__DIR__.'/../helper/enableHyperLink.php');
    require_once(__DIR__.'/../helper/path_to_url.php');
    require_once(__DIR__.'/../api/searchTweets.php');
    //ツイートの検索を行う
    if(isset($_GET['next']) && isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
        $next = $_GET['next'];
        $val = searchTweets($keyword,$SEARCH_TWEET_NUM,$next);
    }else{
        $val = searchTweets($keyword,$SEARCH_TWEET_NUM);
    }
    if($val['error']['flag'] == false){
        $next = $val['datas']['search_metadata']['max_id'];
        foreach($val['datas']['statuses'] as $key => $val){
            echo "<div class='userinfo'>";
            echo $val['user']['name'];
            echo "<a href='https://twitter.com/".$val['user']['screen_name']."' target='_blank'>＠".$val['user']['screen_name']."</a>";
            if($val['user']['protected'] == 1){
                $lock = 1;
                echo '<img src="'.path_to_url(dirname(__DIR__).'/../images/lock.png').'" alt="LOCK" width="32" height="32">';
            }else{
                $lock = 0;
            }
            if(empty($val['user']['profile_image_url'])){
                echo '<div class="center"><span class="profile"><img alt="プロフィール画像未登録" width="50" height="50"></span>';
            }else{
                echo '<div class="center"><span class="profile"><img src='.$val['user']['profile_image_url'].' alt="'.$val['user']['name'].'さんのプロフィール画像" width="50" height="50"></span>';
            }
            if(empty($val['user']['profile_banner_url'])){
                echo '<span class="banner"><img alt="背景画像未登録" width="150" height="50"></span></div>';
            }else{
                echo '<span class="banner"><img src='.$val['user']['profile_banner_url'].' alt="'.$val['user']['name'].'さんの背景画像" width="150" height="50"></span></div>';
            }
            $timestamp = strtotime($val['created_at']);
            $datetime = date('Y-m-d H:i:s', $timestamp);
            echo "<div class='tweet_time'>".$datetime."</div>";
            echo "<div class='tweet_text'>".enableHyperLink($val['text'])."</div>";
            if($val['user']['follow_request_sent'] == true){
                echo "<div class='right'><button class='wait button' data-id='".$val['user']['id']."' data-lock='$lock' data-screen='".$val['user']['screen_name']."'>承認待ち</button></div>";
            }else if($val['user']['following'] == true){
                echo "<div class='right'><button class='destroy button' data-id='".$val['user']['id']."' data-lock='$lock' data-screen='".$val['user']['screen_name']."'>フォロー解除</button></div>";
            }else{
                echo "<div class='right'><button class='follow button' data-id='".$val['user']['id']."' data-lock='$lock' data-screen='".$val['user']['screen_name']."'>フォローする</button></div>";
            }
            echo "</div>";  
        }
        echo "<div id='next' style='display:none;'>".$next."</div>";
        if(!isset($_GET['next'])){
            echo "<div id='keyword' style='display:none;'>".$keyword."</div>";
        }
    }else{
        echo "<div class=\"error_message\">".$val['error']['message']."</div>";
    }