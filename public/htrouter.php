<?php

//if (!file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
//
//    $_GET['_url'] = $_SERVER['REQUEST_URI'];
//}

if (!empty($_SERVER['QUERY_STRING'])) {
    list($uri,$query_strint) = explode('?',$_SERVER['REQUEST_URI']);
} else {
    $uri = $_SERVER['REQUEST_URI'];
}

if (!file_exists(__DIR__ . $uri)) {
    $_GET['_url'] = $uri;
}

return false;
