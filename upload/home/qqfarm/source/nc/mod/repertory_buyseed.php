<?php

# 作物商店

$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$query = $_QFG['db']->query("SELECT exp,package FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$money_1 = $cropstype[$_REQUEST['cId']]['price'] * $_REQUEST['number'];
if($money < $money_1) {
	die('{"cId":0,"code":0,"direction":"您的金币不足！"}');
}

$mylevel = qf_toLevel($list[0]['exp']);
if($mylevel < $cropstype[$_REQUEST['cId']]['cLevel']) {
	die('{"cId":0,"code":0,"direction":"您的等级无法购买该种子！"}');
}

if(in_array($_REQUEST['cId'], $_HIDE['seed'])) {
	die('{"cId":0,"code":0,"direction":"您无权购买此种子！"}');
}

$package = qf_decode($list[0]['package']);
$package[$_REQUEST['cId']] += $_REQUEST['number'];

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money-" . $money_1 . " where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set package='" . qf_encode($package) . "' where uid=" . $_QFG['uid']);

//消费日志
$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
		" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
		", 7, " . $_REQUEST['number'] . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
		", " . $_REQUEST['cId'] . ", 1, '".$money_1."');";
$_QFG['db']->query($sql);

echo '{"code":1,"cId":' . $_REQUEST['cId'] . ',"cName":"' . $cropstype[$_REQUEST['cId']]['cName'] . '","num":' . $_REQUEST['number'] . ',"money":-' . $money_1 . '}';

?>