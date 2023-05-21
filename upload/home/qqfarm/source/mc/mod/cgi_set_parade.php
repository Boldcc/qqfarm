<?php

# 设置队行

$parade['i'] = $_REQUEST['pinfo'];
$parade['p'] = (int)$_REQUEST['pid'];
$parade['v'] = 1;

$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set parade='" . qf_encode($parade) . "' where uid=" . $_QFG['uid']);

echo '{"code":1}';

?>