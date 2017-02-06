<?php
/***** URLを見つけてaタグをつける *****/
function enableHyperLink( $content ) {
    $pattern_http = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/';
    $replace_http = '<a href="\1" target="_blank">\1</a>';
    $content = preg_replace( $pattern_http, $replace_http, $content );
    return $content;
}