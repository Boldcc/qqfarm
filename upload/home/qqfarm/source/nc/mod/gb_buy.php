<?php

# Y币购买

include_once("source/nc/config/toolstype.php");

$qeury = $_QFG['db']->query("SELECT YB,vip FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']);
$value = $_QFG['db']->fetch_array($qeury);
$fb = (int)$value['YB'];
$vip = qf_decode($value['vip']);

$activeitem = $_REQUEST['payitem'];
list($ai, $number) = explode("-", $activeitem);

$type = intval($ai / 10000);
if($type == 4) {//买狗
	$tid = $ai - 40000;
	$query = $_QFG['db']->query("SELECT dog FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	$dogstr = $_QFG['db']->fetch_array($query);
	$dogstr = qf_decode($dogstr['dog']);
	if($tid < 9000) {
		if($vip['status']) {
			$buy_fb = $Toolstype[$ai]["YFBPrice"];
		} else {
			$buy_fb = $Toolstype[$ai]["FBPrice"];
		}
		$tName = $Toolstype[$ai][tName];
		if($buy_fb == 0) {
			die('{"code":50,"msg":"系统出错啦，请刷新以后重试！","payqb":0,"payqp":0,"dnaurl":0}');
			exit();
		}
		if($fb < $buy_fb) {
			die('{"code":50,"msg":"余额不足,请先充值","payqb":0,"payqp":0,"dnaurl":0}');
			exit();
		}
		if($dogstr["dogs"] <> "") {
			if($dogstr["dogs"]->$tid->dogUnWorkTime == 1) {
				die('{"code":50,"msg":"你已经拥有了这条狗了。","payqb":0,"payqp":0,"dnaurl":""}');
				exit();
			}
		}
		if($dogstr["dogFeedTime"] < $_QFG['timestamp']) {
			$dogstr["dogFeedTime"] = $_QFG['timestamp'] + 86400;
		} else {
			$dogstr["dogFeedTime"] = $dogstr["dogFeedTime"] + 86400;
		}
		if($tid == 1) {
			$a = 3;
		} elseif($tid == 3) {
			$a = 1;
		}
		$dogstr["dogs"][$a][status] = 0;
		$dogstr["dogs"][$tid][status] = 1;
		$dogstr["dogs"][$tid][dogUnWorkTime] = 1;
	} else {
		if($vip['status']) {
			$buy_fb = $Toolstype[$ai - 40000]["YFBPrice"];
		} else {
			$buy_fb = $Toolstype[$ai - 40000]["FBPrice"];
		}
		$tName = $Toolstype[$tid]["tName"];
		if($buy_fb == 0) {
			die('{"code":50,"msg":"系统出错啦，请刷新以后重试！","payqb":0,"payqp":0,"dnaurl":0}');
			exit();
		}
		if($fb < $buy_fb ) {
			die('{"code":50,"msg":"余额不足,请先充值","payqb":0,"payqp":0,"dnaurl":0}');
			exit();
		}
		if($ai - 40000 == 9001) {
			$dogFeed = 86400;
		} elseif($ai - 40000 == 9002) {
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
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set yb=" . ($fb-$buy_fb) . " where uid=" . $_QFG['uid']);

	//消费日志
	$payitem = explode('-',$_REQUEST['payitem']);
	$payitem[0] = $payitem[0]>49000 ? ($payitem[0]-40000) : $payitem[0];
	$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
			" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
			", 8, " . $payitem[1] . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
			", '" . $payitem[0] . "', 1, '".$buy_fb."');";
	$_QFG['db']->query($sql);

	die('{"tId":' . $tid . ',"tName":"' . $tName . '","code":0,"direction":"购买成功。","num":' . $number . ',"FB":-' . $buy_fb . ',"money":0,"type":' . $type . '}');

} elseif($type == 2) {//买装饰
	$query = $_QFG['db']->query("SELECT decorative FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	$decorative = $_QFG['db']->fetch_array($query);
	$decorative = qf_decode($decorative['decorative']);
	if($vip['status']) {
		$buy_fb = $itemtype[$ai - 20000][YFBPrice];
	} else {
		$buy_fb = $itemtype[$ai - 20000][FBPrice];
	}
	$buy_time = $itemtype[$ai - 20000][itemValidTime];
	$buy_type = $itemtype[$ai - 20000][itemType];
	$buy_exp = $itemtype[$ai - 20000][exp];
	if($buy_fb == 0) {
		die('{"code":50,"msg":"系统出错啦，请刷新以后重试！","payqb":0,"payqp":0,"dnaurl":0}');
		exit();
	}
	if($fb < $buy_fb) {
		die('{"code":50,"msg":"余额不足,请先充值","payqb":0,"payqp":0,"dnaurl":0}');
		exit();
	}
	foreach($decorative as $item_type => $value) {
		if($buy_type == $item_type) {
			foreach((array)$value as $k => $v) {
				if($key == $ai - 20000) {
					die('{"code":50,"msg":"你已拥有这个装饰，不能重复购买。"}');
					exit();
				} else {
					$ai1 = $ai - 20000;
					$dec = 1;
					$decorative[$item_type][$ai1][status] = 1;
					$decorative[$item_type][$ai1][validtime] = $_QFG['timestamp'] + $buy_time;
				}
			}
			if($dec) {
				foreach((array)$value_arr as $k => $v) {
					if($key != $ai - 20000)
						$decorative[$item_type][$key][status] = 0;
				}
			}
		}
	}

	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp = exp + " . $buy_exp . ",decorative='" . qf_encode($decorative) . "' where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set yb= " . ($fb-$buy_fb) . " where uid=" . $_QFG['uid']);

	//消费日志
	$payitem = explode('-',$_REQUEST['payitem']);
	$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
			" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
			", 8, " . $payitem[1] . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
			", '" . ($payitem[0]-20000) . "', 1, '".$buy_fb."');";
	$_QFG['db']->query($sql);

	echo '{"code":0,"exp":' . $buy_exp . ',"money":0,"FB":-' . $buy_fb . ',"levelUp":false}';
} elseif($type == 3) {//买化肥
	$did = $ai - 30000;
	$query = $_QFG['db']->query("SELECT tools FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	$fertarr = $_QFG['db']->fetch_array($query);
	$fertarr = qf_decode($fertarr[tools]);
	if($vip['status']) {
		$buy_fb = $Toolstype[$ai]["YFBPrice"] * $number;
	} else {
		$buy_fb = $Toolstype[$ai]["FBPrice"] * $number;
	}
	$tName = $Toolstype[$ai][tName];
	if($buy_fb == 0) {
		die('{"code":50,"msg":"系统出错啦，请刷新以后重试！","payqb":0,"payqp":0,"dnaurl":0}');
		exit();
	}
	if($fb < $buy_fb) {
		die('{"code":50,"msg":"余额不足,请先充值","payqb":0,"payqp":0,"dnaurl":0}');
		exit();
	}
	$fertarr[$did] = $fertarr[$did] + $number;

	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set tools='" . qf_encode($fertarr) . "' where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set yb=" . ($fb-$buy_fb) . " where uid=" . $_QFG['uid']);

	//消费日志
	$payitem = explode('-',$_REQUEST['payitem']);
	$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
			" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
			", 8, " . $payitem[1] . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
			", '" . $payitem[0] . "', 1, '".$buy_fb."');";
	$_QFG['db']->query($sql);

	echo '{"tId":' . $did . ',"tName":"' . $tName . '","code":1,"msg":"购买成功。","num":' . $number . ',"fb":-' . $buy_fb . ',"money":0,"type":' . $type . '}';
}


?>