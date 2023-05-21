<?php

# 开通牧场

$vip = $_QFG['db']->result($_QFG['db']->query("SELECT vip FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$vip = qf_decode($vip);

if($vip['status']) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set pf=1 where uid=" . $_QFG['uid']);
	echo '{"code":1}';
} else {
	echo '{"code":0}';
}

?>