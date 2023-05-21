<?php

# 农场公告

$nc_notice = $_NOTICE['nc'];

echo '{"id":' . $_QFG['timestamp'] . ',"content":"' . str_replace('"', '\"', $nc_notice) . '","time":' . $_QFG['timestamp'] . ',"code":1}';

?>