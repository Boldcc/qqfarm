<?php

# 好友动作提示

//好友条件
$condition = ' limit 0,1000';
if($_QSC['friendType'] == 1) {
	$friends = qf_getFriends(0);
	$friends .= (empty($friends) ? '' : ',') . $_QFG['uid'];
	$condition = " WHERE uid IN({$friends})" . $condition;
}

$query = $_QFG['db']->query('SELECT uid,Status,feed,badtime,dabian FROM ' . getTName('qqfarm_mc') . $condition);

while($value = $_QFG['db']->fetch_array($query)) {
	$animal = qf_decode($value['Status']);
	$feed = qf_decode($value['feed']);
	$exp[$value['uid']]['p'] = $value['badtime'];
	$exp[$value['uid']]['b'] = $value['dabian'];
	$exp[$value['uid']]['g'] = 0;
	$exp[$value['uid']]['t'] = 0;

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
//_xieph



	foreach($animal as $key => $value_1) {
		$cId = intval($value_1['cId']);
		if($cId > 0) {
			if( ($_QFG['timestamp'] - $feed['animalfeedtime']) >= $totaltime ) {
			$value_1['growtime'] += $totaltime;
			} else {	
				$value_1['growtime'] += $_QFG['timestamp'] - $feed['animalfeedtime'];	
			}
			$time1 = $animaltime[$value_1['cId']][0] + $animaltime[$value_1['cId']][1];
			$time2 = $animaltime[$value_1['cId']][2];
			$time3 = $value_1['growtime'];
			$time4 = $value_1['growtime'] - $value_1['p'];
			if($time3 > $time1 && $time3 < $time2) {
				if($time4 > $animaltime[$value_1[cId]][4] or $value_1['postTime'] == 0) {
					$temp = $value_1['buyTime'] + $time1;
					if($temp < $g or $g == 0) {
						$exp[$value['uid']]['g'] = $temp;
					}
				}
			}
			$touID = explode(',',$value_1['tou']);
			if(!in_array($_QFG['uid'], $touID)) {
				if($value_1['totalCome'] > $animaltype[$cId]['output'] / 2) {
					$exp[$value['uid']]['t'] = $value_1['postTime'];
				}
			}
		}
	}
}

$exp = qf_getEchoCode($exp);
$int = strlen($exp);
$str = substr($exp, $int - 1, 1);
if($str == ',') {
	$exp = substr($exp, 0, $int - 1);
}
echo '{"msg":"success","result":0,"serverTime":' . $_QFG['timestamp'] . ',"userFlag":' . $exp . '}';

?>