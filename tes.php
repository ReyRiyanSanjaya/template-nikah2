<?php
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$path = $parts['path'];
$keys = explode('/', $path);

var_dump($keys);

parse_str($parts['query'], $qu);
echo $qu['kepada'];
?>