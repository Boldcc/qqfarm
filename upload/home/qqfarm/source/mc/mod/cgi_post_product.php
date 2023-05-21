<?php

# 动物生产

$needlog = 1;
if($_REQUEST['uId'] == null) {
	$_REQUEST['uId'] = $_QFG['uid'];
	$needlog = 0;
}
$serial = intval($_REQUEST['serial']);

$query = $_QFG['db']->query("SELECT Status,feed FROM " . getTName("qqfarm_mc") . " where uid=" . intval($_REQUEST['uId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$animal = qf_decode($list[0]['Status']);
$feed = qf_decode($list[0]['feed']);

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
		$animal[$k1]['growtime']==null && $animal[$k1]['growtime']=$_QFG['timestamp']-$animal[$k1]['buyTime'];
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
foreach($animal as $key => $value) {
	if(0 < $value['cId']) {
		if( ($_QFG['timestamp'] - $feed['animalfeedtime']) >= $totaltime ) {
			$animal[$serial]['p'] = $value['growtime'] + $totaltime;
		} else {	
			$animal[$serial]['p'] = $value['growtime'] + $_QFG['timestamp'] - $feed['animalfeedtime'];	
		}
	}
}


if($feed['animalfood'] <= 0) {
	die( '{"addExp":0,"animal":{"buyTime":'.$animal[$serial]["buyTime"].',"cId":'.$animal[$serial]["cId"].',"growTime":'.$animal[$serial]["p"].',"growTimeNext":'.$animaltime[$animal[$serial]["cId"]][3].',"hungry":1,"serial":'.$serial.',"status":3,"statusNext":6,"totalCome":'. $animaltype[$animal[$serial]["cId"]]["output"] .'},"errorCode":"-11000","errorContent":"动物挨饿啦，缺少牧草会停止生产，快去添加","errorType":"1011"}');
}
if($_QFG['timestamp'] - $animal[$serial]['postTime'] <= $animaltime[$animal[$serial]['cId']][3]) {
	die( '{"addExp":0,"animal":{"buyTime":'.$animal[$serial]["buyTime"].',"cId":'.$animal[$serial]["cId"].',"growTime":'.$animal[$serial]["p"].',"growTimeNext":'.$animaltime[$animal[$serial]["cId"]][3].',"hungry":1,"serial":'.$serial.',"status":3,"statusNext":6,"totalCome":'. $animaltype[$animal[$serial]["cId"]]["output"] .'},"errorCode":"-11002","errorContent":"您下手太慢啦,已被处理.","errorType":"1016"}');
}

/*if(($animal[$serial]['postTime'] + $animaltime[$animal[$serial]['cId']][4]) > $_QFG['timestamp']) {
	die( '{"addExp":0,"animal":{"buyTime":'.$animal[$serial]["buyTime"].',"cId":'.$animal[$serial]["cId"].',"growTime":'.$animal[$serial]["p"].',"growTimeNext":'.$animaltime[$animal[$serial]["cId"]][3].',"hungry":0,"serial":'.$serial.',"status":3,"statusNext":6,"totalCome":'. $animaltype[$animal[$serial]["cId"]]["output"] .'},"errorCode":"-11000","errorContent":"请不要采用非法手段！","errorType":"1011"}');
}
*/
//防加速齿轮
if($animal[$serial]['postTime'] == 0) {
	$chk_time = $animal[$serial]['growtime'];
	if($chk_time < $animaltime[$animal[$serial]['cId']][0] + $animaltime[$animal[$serial]['cId']][1]) {
		die( '{"addExp":0,"animal":{"buyTime":'.$animal[$serial]["buyTime"].',"cId":'.$animal[$serial]["cId"].',"growTime":'.$animal[$serial]["growtime"].',"growTimeNext":'.$animaltime[$animal[$serial]["cId"]][3].',"hungry":0,"serial":'.$serial.',"status":3,"statusNext":6,"totalCome":'. $animaltype[$animal[$serial]["cId"]]["output"] .'},"errorCode":"-11000","errorContent":"还没到生产的时间呢，请不要着急","errorType":"1011"}');
	}
} else {
	if( ($animal[$serial]['postTime'] + 3600 > $_QFG['timestamp'])) {
		die( '{"addExp":0,"animal":{"buyTime":'.$animal[$serial]["buyTime"].',"cId":'.$animal[$serial]["cId"].',"growTime":'.$animal[$serial]["growtime"].',"growTimeNext":'.$animaltime[$animal[$serial]["cId"]][3].',"hungry":0,"serial":'.$serial.',"status":3,"statusNext":6,"totalCome":'. $animaltype[$animal[$serial]["cId"]]["output"] .'},"errorCode":"-11000","errorContent":"还没到生产的时间呢，请不要着急","errorType":"1011"}');
	}
}
if( $animal[$serial]['growtime']-$animal[$serial]['p'] >= $animaltime[$animal[$serial]['cId']][4]) {
    $animal[$serial]['p']=$animal[$serial]['growtime'];
}
//入库
$animal[$serial]['postTime'] = $_QFG['timestamp'];
$animal[$serial]['tou'] = "";
$animal[$serial]['totalCome'] = $animal[$serial]['totalCome'] + $animaltype[$animal[$serial]['cId']]['output'];
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "' where uid=" . intval($_REQUEST['uId']));

$add_exp = $_REQUEST['uId'] == $_QFG['uid'] ? 5 : 2;
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set exp=exp+" . $add_exp . " where uid=" . $_QFG['uid']);


//帮产日志
if($_QFG['uid'] != $_REQUEST['uId']) {
	$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . intval($_REQUEST['uId']) . " AND type = 2 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		if(($value[type] == 2) && ($value[fromid] == $_QFG['uid'])) {
			// &&($value[iid]) &&($value[count])
			$list = explode(",", $value[iid]);
			$scount = explode(",", $value[count]);
			$stime = $value[time];
			$listo = "";
			$scounto = "";
			$flag = 0;
			for($i = 0; $i < count($list); $i++) {
				if($list[$i] == $animal[$serial][cId]) {
					$flag = 1;
					$scount[$i] = $scount[$i] + 1;
				}
			}
			if($flag == 0) {
				$list[count($list)] = $animal[$serial][cId];
				$scount[count($list)] = 1;
			}
			for($i = 0; $i < (count($list)) - 1; $i++) {
				$listo = $listo . $list[$i] . ",";
				$scounto = $scounto . $scount[$i] . ",";
			}
			$listo = $listo . $list[count($list) - 1];
			$scounto = $scounto . $scount[count($list) - 1];
			$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set iid = '" . $listo . "', count = '" . $scounto . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . intval($_REQUEST['uId']) . " AND type = 2 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
		}
	}
	if(($listo == "") || ($scounto == "")) {
		$listo = $animal[$serial][cId];
		$scounto = 1;
	}
	if(!$sql1) {
		$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $_REQUEST['uId'] . ", 2, " . $scounto . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", " . $listo . ", 0, 0);";
	}
	if($sql1) $query = $_QFG['db']->query($sql1);
}

//返回信息
echo '{"addExp":' . $add_exp . ',"animal":{"buyTime":' . $animal[$serial]['buyTime'] . ',"cId":' . $animal[$serial][cId] . ',"createTime":0,"feedTime":' . ($_QFG['timestamp'] - $animaltime[$animal[$serial][cId]][0]) . ',"growTime":' . ($animal[$serial]['growtime']) . ',"growTimeNext":' . $animaltime[$animal[$serial][cId]][3] . ',"postTime":' . $_QFG['timestamp'] . ',"productNum":2,"serial":' . $serial . ',"status":4,"statusNext":5,"totalCome":' . $animaltype[$animal[$serial]['cId']]['output'] . '}}';

?>