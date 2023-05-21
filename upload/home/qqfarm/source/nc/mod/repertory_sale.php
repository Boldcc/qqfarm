<?php

# 单个卖出

$fruit = $_QFG['db']->result($_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$fruit = qf_decode($fruit);

if($fruit[$_REQUEST['cId']] < $_REQUEST['number']) {
	die('{"cId":0,"code":0,"direction":"请确认数值！"}');
}

$fruit[$_REQUEST['cId']] -= $_REQUEST['number'];
foreach($fruit as $key => $value) {
	if($value == 0) {
		unset($fruit[$key]);
	}
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money+" . $cropstype[$_REQUEST['cId']][sale] * $_REQUEST['number'] . " where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit='" . qf_encode($fruit) . "' where uid=" . $_QFG['uid']);


//出售日志
$_QFG['db']->query("INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_QFG['uid'] . ", 6, " . $_REQUEST['number'] . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", " . $_REQUEST['cId'] . ", 1);");

echo '{"cId":' . $_REQUEST['cId'] . ',"code":1,"direction":"成功卖出<font color=\"#0099FF\"> <b>' . $_REQUEST['number'] . '<\/b> <\/font>个' . $cropstype[$_REQUEST['cId']][cName] . '，得到金币<font color=\"#FF6600\"> <b>' . $cropstype[$_REQUEST['cId']][sale] * $_REQUEST['number'] . '<\/b> <\/font>","money":' . $cropstype[$_REQUEST['cId']][sale] * $_REQUEST['number'] . '}';

qf_addFeed('farmlandstatus_sale');

?>