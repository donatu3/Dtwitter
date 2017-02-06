<?php
    if(!isset($_SESSION)){
        session_start();
    }

    require_once(__DIR__.'/../config/parameters.php');
    require_once(__DIR__.'/../helper/enableHyperLink.php');
    require_once(__DIR__.'/../helper/path_to_url.php');
    require_once(__DIR__.'/../api/getFriendIds.php');
    require_once(__DIR__.'/../api/getConnection.php');
    require_once(__DIR__.'/../api/getUsersInfo.php');
    //自分のidからフォローしている人のidを検索
    if(isset($_GET['next_cursor'])){
        $next_cursor = $_GET['next_cursor'];
        $friends = getFriendIds($_SESSION['user_id'],$GET_FOLLOW_NUM,$next_cursor);
    }else{
        $friends = getFriendIds($_SESSION['user_id'],$GET_FOLLOW_NUM);
    }
    $empty_error_flag = false;
    if($friends['error']['flag'] == true){
        $friend_datas['error']['flag'] = true;
        $friend_datas['error']['message'] = $friends['error']['message'];
    }else{
        //得られたidとの関係チェック
        $next_cursor = $friends['next_cursor'];
        $friends = getConnection($friends['datas']['ids']);
        if($friends['error']['flag'] == true){
            $friend_datas['error']['flag'] = true;
            $friend_datas['error']['message'] = $friends['error']['message'];
        }else{
            $one_way_ids = array();
            //相手からフォローされていない場合のみ取り出す
            foreach($friends['datas'] as $val){
                if(!in_array('followed_by',$val['connections'])){
                    array_push($one_way_ids,$val['id']);
                }
            }
            if(!empty($one_way_ids)){
                $friend_datas = getUsersinfo($one_way_ids);
            }else{
                //一方的フォローを検索しても誰もいない場合がある
                $empty_error_flag = true;
                $friend_datas['error']['flag'] = false;
            }
        }
    }
    if($friend_datas['error']['flag'] == false){
        if($empty_error_flag == false){
            foreach($friend_datas['datas'] as $key => $val){
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
                echo "<div class='right'><button class='destroy button' data-id='".$val['id']."' data-lock='$lock' data-screen='".$val['screen_name']."'>フォロー解除</button></div>";
                echo "</div>";
            }
        }
        echo "<div id='next' style='display:none;'>".$next_cursor."</div>";
    }else{
        echo "<div class=\"error_message\">".$friend_datas['error']['message']."</div>";
    }