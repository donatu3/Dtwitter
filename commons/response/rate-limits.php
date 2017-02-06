<?php
    if(!isset($_SESSION)){
        session_start();
    }

    require_once(__DIR__.'/../api/getRateLimits.php');
    //APIの使用状況を調べる
    $val = getRateLimits(array("application","friendships","users","followers","friends","search"));
    if($val['error']['flag'] == false){
        echo "<h3>片思い確認の残り回数</h3>";
        $rm_friendships = $val['datas']['resources']['friendships']['/friendships/lookup']['remaining'];
        $rs_friendships = $val['datas']['resources']['friendships']['/friendships/lookup']['reset'];
        $rm_user = $val['datas']['resources']['users']['/users/lookup']['remaining'];
        $rs_user = $val['datas']['resources']['users']['/users/lookup']['reset'];
        $rm_friends = $val['datas']['resources']['friends']['/friends/ids']['remaining'];
        $rs_friends = $val['datas']['resources']['friends']['/friends/ids']['reset'];
        echo min($rm_friendships,$rm_user,$rm_friends);
        echo "/";
        echo $val['datas']['resources']['friends']['/friends/ids']['limit'];
        $timestamp = max($rs_friendships,$rs_user,$rs_friends);
        echo "<br>リセット予定時刻：".date('Y-m-d H:i:s', $timestamp);
        echo "<h3>片思われ確認の残り回数</h3>";
        echo $val['datas']['resources']['followers']['/followers/list']['remaining'];
        echo "/";
        echo $val['datas']['resources']['followers']['/followers/list']['limit'];
        $timestamp = $val['datas']['resources']['followers']['/followers/list']['reset'];
        echo "<br>リセット予定時刻：".date('Y-m-d H:i:s', $timestamp);
        echo "<h3>検索の残り回数</h3>";
        echo $val['datas']['resources']['search']['/search/tweets']['remaining'];
        echo "/";
        echo $val['datas']['resources']['search']['/search/tweets']['limit'];
        $timestamp = $val['datas']['resources']['search']['/search/tweets']['reset'];
        echo "<br>リセット予定時刻：".date('Y-m-d H:i:s', $timestamp);
        echo "<h3>API状況確認の残り回数</h3>";
        echo $val['datas']['resources']['application']['/application/rate_limit_status']['remaining'];
        echo "/";
        echo $val['datas']['resources']['application']['/application/rate_limit_status']['limit'];
        $timestamp = $val['datas']['resources']['application']['/application/rate_limit_status']['reset'];
        echo "<br>リセット予定時刻：".date('Y-m-d H:i:s', $timestamp);
    }else{
        echo "<div class=\"error_message\">".$val['error']['message']."</div>";
    }