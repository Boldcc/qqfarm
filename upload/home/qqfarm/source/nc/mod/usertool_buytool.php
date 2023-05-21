<?php

# 狗粮购买

include_once("source/nc/config/toolstype.php");

if($_REQUEST['type'] == 3) {
	$rtid = 30000 + $_REQUEST['tId'];
} elseif($_REQUEST['type'] == 4) {
	$rtid = 40000 + $_REQUEST['tId'];
} else {
	$rtid = $_REQUEST['tId'];
}

if($Toolstype[$rtid]["saleOut"] == true) {
	die('{"code":0,"direction":"已经售完咯，请及时关注农场公告！","payqb":0,"payqp":0,"dnaurl":""}');
}

$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);

if($_REQUEST['type'] == 3) {
	$query = $_QFG['db']->query("SELECT tools FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	$buy_money = $Toolstype[30000 + $_REQUEST['tId']]["price"] * $_REQUEST['number'];
	$tName = $Toolstype[30000 + $_REQUEST['tId']]["tName"];
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	if($money < $buy_money) {
		exit();
	}
	$fertarr = qf_decode($list[0]['tools']);
	$fertarr[$_REQUEST['tId']] = $fertarr[$_REQUEST['tId']] + $_REQUEST['number'];
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set tools='" . qf_encode($fertarr) . "' where uid=" . $_QFG['uid']);
	//消费日志
	$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
			" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
			", 12, ".$_REQUEST['number'].", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
			", '" . (30000+$_REQUEST['tId']) . "', 1, '".$buy_money."');";
	$_QFG['db']->query($sql);
} elseif($_REQUEST['type'] == 4) {
	$query = $_QFG['db']->query("SELECT dog FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$dogstr = qf_decode($list[0]['dog']);
	$i = $dogstr['dogs'][$_REQUEST['tId']]['dogUnWorkTime'];
	if($_REQUEST['tId'] < 9000) {
		$buy_money = $Toolstype[40000 + $_REQUEST['tId']]['price'] * $_REQUEST['number'];
		if($money < $buy_money) {
			exit();
		}
		$tName = $Toolstype[40000 + $_REQUEST['tId']]['tName'];
		if($dogstr['dogFeedTime'] < $_QFG['timestamp']) {
			$dogstr['dogFeedTime'] = $_QFG['timestamp'] + 86400;
		} elseif($i == 1) {
			echo '{"direction":"只能购买一只狗哟"}';
		}
		if($_REQUEST['tId'] == 1) {
			$a = 1;
		} elseif($_REQUEST['tId'] == 3) {
			$a = 3;
		}
		$dogstr["dogs"][$a]['status'] = 0;
		$dogstr["dogs"][$_REQUEST['tId']]['status'] = 1;
		$dogstr["dogs"][$_REQUEST['tId']]['dogUnWorkTime'] = 1;
	} else {
		$buy_money = $Toolstype[$_REQUEST['tId']]["price"] * $_REQUEST['number'];
		if($money < $buy_money) {
			exit();
		}
		$tName = $Toolstype[$_REQUEST['tId']]["tName"];
		if($_REQUEST['tId'] == 9001) {
			$dogFeed = 86400;
		} elseif($_REQUEST['tId'] == 9002) {
			$dogFeed = 604800;
		} else {
			$dogFeed = 0;
		}
		if($dogstr["dogFeedTime"] < $_QFG['timestamp']) {
			$dogstr["dogFeedTime"] = $_QFG['timestamp'] + $dogFeed;
		} else {
			$dogstr["dogFeedTime"] = $dogstr["dogFeedTime"] + $dogFeed;
		}
	}
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set dog='" . qf_encode($dogstr) . "' where uid=" . $_QFG['uid']);
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money - " . $buy_money . " where uid=" . $_QFG['uid']);

echo '{"tId":' . $_REQUEST['tId'] . ',"tName":"' . $tName . '","code":1,"direction":"购买成功。","num":1,"money":-' . $buy_money . ',"FB":0,"type":' . $_REQUEST['type'] . '}';

?>