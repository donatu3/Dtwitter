<?php
    /***** セッション変数を全て削除する *****/
    if(!isset($_SESSION)){
        session_start();
    }
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
?>