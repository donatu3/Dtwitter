<?php
function path_to_url($path, $default_port = 80){
    //ドキュメントルートのパスとURLの作成
    $document_root_url = $_SERVER['SCRIPT_NAME'];
    $document_root_path = $_SERVER['SCRIPT_FILENAME'];
    while(basename($document_root_url) === basename($document_root_path)){
        $document_root_url = dirname($document_root_url);
        $document_root_path = dirname($document_root_path);
    }
    if($document_root_path === '/')  $document_root_path = '';
    if($document_root_url === '/') $document_root_url = '';
    $protocol = 'http';
    $port = ($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != $default_port)? ':'.$_SERVER['SERVER_PORT']: '';
    $document_root_url = $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$document_root_url;
    //絶対パスの取得 (realpath関数ではファイルが存在しない場合や、シンボリックリンクである場合にうまくいかない)
    $absolute_path = realpath($path);
    if(!$absolute_path)
        return false;
    if(substr($absolute_path, -1) !== '/' && substr($path, -1) === '/')
        $absolute_path .= '/';
    //パスを置換して返す
    $url = str_replace($document_root_path, $document_root_url, $absolute_path);
    if($absolute_path === $url)
        return false;
    return $url;
}
?>