<?php

# 牧场公告

$mc_notice = $_NOTICE['mc'];
echo '{"code":1,"content":"' . str_replace('"', '\"', $mc_notice) . '","time":' . $_QFG['timestamp'] . ',"id":' . $_QFG['timestamp'] . '}';

?>