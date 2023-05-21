<?php

# 全部卖出

$query = $_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$fruit = qf_decode($list[0]['fruit']);

$money = 0;
$sellary = explode(',', $_REQUEST['cIds']);
foreach($fruit as $key => $value) {
	if(0 < $value && $cropstype[$key]['cType'] == 1 && in_array($key, $sellary)) {
		$money = $money + $cropstype[$key]['sale'] * $value;
		$_QFG['db']->query("INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_QFG['uid'] . ", 6, '".$value."', " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ",'".$key."', 1);");
		unset($fruit[$key]);
	}
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money+" . $money . " where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit='" . qf_encode($fruit) . "' where uid=" . $_QFG['uid']);

echo '{"code":1,"direction":"","money":' . $money . '}';

qf_addFeed('farmlandstatus_saleall');

?>