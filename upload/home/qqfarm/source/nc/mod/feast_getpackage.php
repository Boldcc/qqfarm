<?php

# 礼包提示

$query = $_QFG['db']->query("SELECT package,tools,dog,taskid FROM  " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$package = qf_decode($list[0]['package']);
$tools = qf_decode($list[0]['tools']);
$dogstr = qf_decode($list[0]['dog']);
$taskid = $list[0]['taskid'];

if($taskid == 0) {
	$cId = 7;
	$package[$cId ]= $package[$cId] + 2;
	$cId1 = 1;
	$tools[$cId1] = $tools[$cId1] + 4;
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set tools='" . qf_encode($tools) . "', package='" . qf_encode($package) . "',taskid=1 where uid=" . $_QFG['uid']);
	echo '{"direction":"","item":[{"eNum":4,"eParam":1,"eType":3},{"eNum":2,"eParam":7,"eType":1}],"title":"","vip":0,"vipItem":[],"vipText":""}';
	exit();
}

//读VIP级别，根据VIP级别送不同的
$vip = $_QFG['db']->result($_QFG['db']->query("SELECT vip FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$vip = qf_decode($vip);
if((int)$vip['rsign'] == 0) {
	exit();
}

switch(qf_toVipLevel($vip['exp'])) {
	case 1:
		$cId = 41;
		$package[$cId ]= $package[$cId] + 1;
		$item = '[{"eNum":1,"eParam":41,"eType":1}]';
		break;
	case 2:
		$cId = 1;
		$tools[$cId] = $tools[$cId] + 3;
		$item = '[{"eNum":3,"eParam":1,"eType":3}]';
		break;
	case 3:
		$cId = 1;
		$tools[$cId] = $tools[$cId] + 4;
		$item = '[{"eNum":4,"eParam":1,"eType":3}]';
		break;
	case 4:
		$cId = 1;
		$tools[$cId] = $tools[$cId] + 5;
		$item = '[{"eNum":4,"eParam":1,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
	case 5:
		$cId = 2;
		$tools[$cId ]= $tools[$cId] + 5;
		$item = '[{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
	case 6:
		$cId = 1;
		$tools[$cId] = $tools[$cId] + 5;
		$cId1 = 2;
		$tools[$cId1] = $tools[$cId1] + 5;
		$item = '[{"eNum":5,"eParam":1,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
	case 7:
		$cId = 2;
		$tools[$cId] = $tools[$cId ]+ 5;
		$cId1 = 3;
		$tools[$cId1] = $tools[$cId1] + 5;
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
	case 8:
		$cId = 2;
		$tools[$cId] = $tools[$cId] + 1;
		$cId1 = 3;
		$tools[$cId1] = $tools[$cId1] + 1;
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
	case 9:
		$cId = 2;
		$tools[$cId] = $tools[$cId] + 1;
		$cId1 = 3;
		$tools[$cId1] = $tools[$cId1] + 1;
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr['dogFeedTime'] = $dogstr['dogFeedTime'] + 86400;
		}
		break;
}

$vip['rsign'] = 0;
$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set vip='".qf_encode($vip)."' where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set tools='" . qf_encode($tools) . "', package='" . qf_encode($package) . "', dog='" . qf_encode($dogstr) . "' where uid=" . $_QFG['uid']);

echo '{"direction":"","item":' . $item . ',"title":"","vip":' . qf_toVipLevel($vip['exp']) . ',"vipItem":[],"vipText":""}';

?>