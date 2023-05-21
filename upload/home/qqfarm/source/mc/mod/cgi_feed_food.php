<?php

# 帮自己、好友加草

if(!is_numeric($_REQUEST['foodnum']) || $_REQUEST['foodnum'] < 1) {
	exit();
}
$foodnum = $_REQUEST['foodnum'] < 401 ? $_REQUEST['foodnum'] : 400;

if($_REQUEST['uId'] && $_QFG['uid'] != $_REQUEST['uId']) {
	$uId = (int)$_REQUEST['uId'];
	$toFriend = true;
} else $uId = $_QFG['uid'];

if($_REQUEST['type'] == "0") {
	$fruit = $_QFG['db']->result($_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$fruit = qf_decode($fruit);
	$id = 40;
	if($fruit[$id] < 1 || $fruit[$id] < $foodnum) {
		die('{"errorContent":"背包中牧草数量不足，请刷新查看你实际牧草数！","errorType":"1011"}');
	} else {
		$mucaoid = 40;
		$query = $_QFG['db']->query("SELECT Status,feed FROM " . getTName("qqfarm_mc") . " where uid=" . $uId);
		while($value = $_QFG['db']->fetch_array($query)) {
			$list[] = $value;
		}
		$animal = qf_decode($list[0]['Status']);
		$feed = qf_decode($list[0]['feed']);
		if(401 < $feed['animalfood'] + $foodnum) {
			$foodnum = ceil(400 - $feed['animalfood']);
		}
		$feed['animalfood'] = $feed['animalfood'] + $foodnum;
		$fruit[$mucaoid] = $fruit[$mucaoid] - $foodnum;
		if($foodnum == 0) {
			die('{"errorContent":"已经加到上限了，有多的草给朋友加点吧……","errorType":"1011"}');
		}
		$addExp = 0;
		if($_POST['uId'] != $_QFG['uid']) {
			$addExp = floor($foodnum / 10);
		}
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit='" . qf_encode($fruit) . "' where uid=" . $_QFG['uid']);
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set exp=exp+" . $addExp . " where uid=" . $_QFG['uid']);
		$newanimal = getNewAnimal();
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',feed='".qf_encode($feed)."' where uid=" . $uId);
		//加草日志
		if($toFriend) {
			$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . $uId . " AND type = 3 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
			$query = $_QFG['db']->query($sql);
			while($value = $_QFG['db']->fetch_array($query)) {
				if(($value[type] == 3) && ($value[fromid] == $_QFG['uid']) && ($foodnum > 0)) {
					$scount = $value[count];
					$stime = $value[time];
					$scount = $scount + $foodnum;
					$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . $uId . " AND type = 3 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
				}
			}
			if((!$sql1) && ($foodnum > 0)) {
				$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $uId . ", 3," . $foodnum . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 40, 0, 0);";
			}
			if($sql1) $query = $_QFG['db']->query($sql1);
		}
		//输出信息
		die('{"addExp":' . $addExp . ',"added":' . $foodnum . ',"animal":' . $newanimal . ',"direction":"成功添加<font color=\"#009900\"> <b>' . $foodnum . '</b> </font>棵牧草","money":0,"total":' . $feed[animalfood] . ',"type":0,"uId":' . $uId . '}');
	}
}
elseif($_REQUEST['type'] == '1') {
	$mc_price = 60;
	$mc_id = 40;
	$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
	if($money < $mc_price * $foodnum) {
		die('{"errorContent":"你的金币不足，购买' . $foodnum . '棵牧草，共需要' . ($mc_price * $foodnum) . '个金币。","errorType":"1011"}');
	}
	$money = $money - ($mc_price * $foodnum);
	$query = $_QFG['db']->query("SELECT Status,feed FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$animal = qf_decode($list[0]['Status']);
	$feed = qf_decode($list[0]['feed']);
	if(401 < $feed['animalfood'] + $foodnum) {
		$foodnum = ceil(400 - $feed['animalfood']);
	}
	$feed[animalfood] = $feed['animalfood'] + $foodnum;
	$newanimal = getNewAnimal();
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=" . $money . " where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',feed='".qf_encode($feed)."' where uid=" . $_QFG['uid']);
	//加草日志
	$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . $_QFG['uid'] . " AND type = 4 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		if(($value[type] == 4) && ($value[fromid] == $_QFG['uid']) && ($foodnum > 0)) {
			$money = $value[money];
			$scount = $value[count];
			$stime = $value[time];
			$money = $money + ($mc_price * $foodnum);
			$scount = $scount + $foodnum;
			$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set money = '" . $money . "', count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 1 where uid = " . $_QFG['uid'] . " AND type = 4 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
		}
	}
	if((!$sql1) && ($foodnum > 0)) {
		$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $_QFG['uid'] . ", 4," . $foodnum . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 40, 1, " . ($mc_price * $foodnum) . ");";
	}
	if($sql1) $query = $_QFG['db']->query($sql1);
	//输出信息
	echo stripslashes('{"addExp":0,"added":0,"alert":"成功购买' . $foodnum . '棵牧草，共花费金币' . ($mc_price * $foodnum) . '，已放入您的饲料机内。","animal":' . $newanimal . ',"money":' . ($mc_price * $foodnum) . ',"total":' . $feed[animalfood] . ',"type":1,"uId":' . $_QFG['uid'] . '}');
} elseif($_REQUEST['type'] == '2') {
	$mc_price = 60;
	$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
	if($money < $mc_price * $foodnum) {
		die('{"errorContent":"你的金币不足，购买' . $foodnum . '棵牧草，共需要' . ($mc_price * $foodnum) . '个金币。","errorType":"1011"}');
	}
	$money = $money - ($mc_price * $foodnum);
	$query = $_QFG['db']->query("SELECT Status,feed FROM " . getTName("qqfarm_mc") . " where uid=" . $uId);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$animal = qf_decode($list[0]['Status']);
	$feed = qf_decode($list[0]['feed']);
	if($feed[animalfood] >= 400) {
		die('{"errorContent":"已经加到上限了，有多的草给别的朋友加点吧……","errorType":"1011"}');
	}
	if(401 < $feed[animalfood] + $foodnum) {
		$foodnum = ceil(400 - $feed[animalfood]);
	}
	$feed[animalfood] = $feed[animalfood] + $foodnum;
	$newanimal = getNewAnimal();
	$addExp = 0;
	if($toFriend) {
		$addExp = floor($foodnum / 10);
	}
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=" . $money . " where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set exp=exp+" . $addExp . " where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',feed='".qf_encode($feed)."' where uid=" . $uId);
	//加草日志
	if($toFriend) {
		$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . $uId . " AND type = 5 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
		$query = $_QFG['db']->query($sql);
		while($value = $_QFG['db']->fetch_array($query)) {
			if(($value[type] == 5) && ($value[fromid] == $_QFG['uid']) && ($foodnum > 0)) {
				$money = $value[money];
				$scount = $value[count];
				$stime = $value[time];
				$money = $money + ($mc_price * $foodnum);
				$scount = $scount + $foodnum;
				$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set money = '" . $money . "', count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . $uId . " AND type = 5 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
			}
		}
		if((!$sql1) && ($foodnum > 0)) {
			$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $uId . ", 5," . $foodnum . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 40, 0, " . ($mc_price * $foodnum) . ");";
		}
		if($sql1) $query = $_QFG['db']->query($sql1);
	}
	//输出信息
	echo stripslashes('{"addExp":' . $addExp . ',"added":0,"animal":' . $newanimal . ',"alert":"成功购买' . $foodnum . '棵牧草，共花费金币' . ($mc_price * $foodnum) . '，已放入您的饲料机内。","money":' . ($mc_price * $foodnum) . ',"total":' . $feed[animalfood] . ',"type":2,"uId":' . $uId . '}');
}

//共用部分::更新全局$animal,$feed,并获取$newanimal
function getNewAnimal() {
	global $_QFG, $animaltime, $animaltype, $animal, $feed;
$needfood = $hourfood = $totaltime = $hungry = 0;
//xieph 计算动物食用的总时间
foreach($animal as $k => $v) {
	$v['cId'] > 0 && $hourfood += $animaltype[$v['cId']]['consum'] /4 ; //动物每小时所需要的食物
}
$totaltime = $feed['animalfood'] / $hourfood * 3600; //totaltime:当前食物供动物食用的总时间 
$need = 0; //距动物成熟所需要的草
$harvestarr = array();
foreach($animal as $k1 => $v1) { //计算是否有动物即将可收获
	if($v1['cId'] > 0) {
		$growtime = 0;
		if(($_QFG['timestamp'] -  $feed['animalfeedtime']) >= $totaltime ) {
			$growtime = $v1['growtime'] + $totaltime;
			if($growtime >= $animaltime[$v1['cId']][5]) {
				$need += ($animaltime[$v1['cId']][5] - $v1['growtime'])>0 ? ($animaltime[$v1['cId']][5] - $v1['growtime']) / 3600 * $animaltype[$v1['cId']]['consum'] / 4 : 0;
				$harvestarr[] = $k1;
			}
		} else {
			$growtime += $v1['growtime'] + ($_QFG['timestamp'] - $feed['animalfeedtime']);
			if($growtime >= $animaltime[$v1['cId']][5]) {
				$need += ($animaltime[$v1['cId']][5] - $v1['growtime']) > 0 ? ($animaltime[$v1['cId']][5] - $v1['growtime']) / 3600 * $animaltype[$v1['cId']]['consum'] /4 : 0;
				$harvestarr[] = $k1;
			}
		}
	}
}

if($harvestarr) {
	$hourfood = 0;
	foreach($animal as $k2 => $v2) {
		if($v2['cId']>0 && !in_array($k2, $harvestarr)) {
			$hourfood += $animaltype[$v2['cId']]['consum'] / 4;
		}
	}
	if($hourfood>0) {
		$totaltime = ($feed['animalfood'] - $need) / $hourfood * 3600;
	}
}
//_xieph

	foreach($animal as $key => $value) {
		if(0 < $value['cId']) {
			// xieph 增加growtime:动物的成长时间

		$growtime1 = $value['growtime'];
		if( ($_QFG['timestamp'] - $feed['animalfeedtime']) >= $totaltime ) {
			$value['growtime'] += $totaltime;
			if($value['growtime'] >= $animaltime[$value['cId']][5]) {
				$value_feedtime = $animaltime[$value['cId']][5]-$growtime1;
			} else {
				$value_feedtime = $totaltime;
			}
			$hungry = 1;
		} else {	
			$value['growtime'] += $_QFG['timestamp'] - $feed['animalfeedtime'];	
			if($value['growtime'] >= $animaltime[$value['cId']][5] ) {
				$value_feedtime = $animaltime[$value['cId']][5]-$growtime1;
			} else {
				$value_feedtime = $_QFG['timestamp'] - $feed['animalfeedtime'];
			}
			
			$hungry = 0;
		}
		$needfood = $value_feedtime / 3600 * $animaltype[$value['cId']]['consum'] / 4;
		$needfood = $needfood > 0 ? $needfood : 0;

		$feed['animalfood'] -= $needfood;
			$totalCome = $value['totalCome'];
			if($value['postTime'] == 0) {
				if($animaltime[$value['cId']][0] + $animaltime[$value['cId']][1] <= $value['growtime']) {
					$status = 3;
					$growTimeNext = 12993;
					$statusNext = 6;
				}
				if($animaltime[$value['cId']][0] <= $value['growtime'] && $value['growtime'] < $animaltime[$value['cId']][0] + $animaltime[$value['cId']][1]) {
					$status = 2;
					$growTimeNext = $animaltime[$value['cId']][0] + $animaltime[$value['cId']][1] - $value['growtime'];
					$statusNext = 3;
				}
				if($value['growtime'] < $animaltime[$value['cId']][0]) {
					$status = 1;
					$growTimeNext = $animaltime[$value['cId']][0] - $value['growtime'];
					$statusNext = 2;
				}
				if($animaltime[$value['cId']][5] < $value['growtime']) {
					$status = 6;
					$growTimeNext = 0;
					$statusNext = 6;
				}
			} else {
				$ptime = $value['growtime']-$value['p'];
				if($animaltime[$value['cId']][5] <= $value['growtime']) {
					$status = 6;
					$statusNext = 6;
					$growTimeNext = 0;
				}
				if($animaltime[$value['cId']][4] <= $ptime) {
					$status = 3;
					$statusNext = 6;
					$growTimeNext = 12993;
				}
				if($ptime <= $animaltime[$value['cId']][4]) {
					$status = 5;
					$statusNext = 3;
					$growTimeNext = $animaltime[$value['cId']][4] - $ptime;
				}
				if($ptime <= $animaltime[$value['cId']][3]) {
					$status = 4;
					$statusNext = 5;
					$growTimeNext = $animaltime[$value['cId']][3] - $ptime;
					$totalCome -= $animaltype[$value['cId']][output];
				}
				if($animaltime[$value['cId']][5] - $animaltime[$value['cId']][3] - $animaltime[$value['cId']][4] < $value['growtime']) {
					$status = 5;
					$statusNext = 6;
					$growTimeNext = $animaltime[$value['cId']][5] - $value['growtime'];
				}
			}
			//_xieph
			$newanimal[] = array('buyTime'=>$value['buyTime'],'cId'=>$value['cId'],'growTime'=>$value['growtime'],'growTimeNext'=>$growTimeNext,'hungry'=>$hungry,'serial'=>$key,'status'=>$status,'statusNext'=>$statusNext,'totalCome'=>$totalCome);
			$animal[$key] = $value;//更新参数
		}
	}
	$feed['animalfood'] = ceil($feed['animalfood']);
	$GLOBALS['feed'] = $feed;
	$GLOBALS['animal'] = array_values($animal);
	$newanimal = str_replace('null', '[]', qf_getEchoCode($newanimal));
	return $newanimal;
}

?>