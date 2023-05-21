<?php

# 访问自己和别人农场

if($_REQUEST['ownerId'] && $_QFG['uid'] != $_REQUEST['ownerId']) {
	$toFriend = true;
	$uId = (int)$_REQUEST['ownerId'];
}
else $uId = $_QFG['uid'];

if($toFriend) {
	$query = $_QFG['db']->query('SELECT C.uid,C.username,C.money,C.pf,C.yb,N.Status,N.reclaim,N.exp,N.taskid,N.badnum,N.dog,N.decorative,N.activeItem,N.healthMode FROM  ' . getTName('qqfarm_config') . ' C Left JOIN ' . getTName('qqfarm_nc') . ' N ON N.uid=C.uid where C.uid=' . $uId);
} else {
	$query = $_QFG['db']->query('SELECT C.money,C.username,C.uid,C.yb,C.vip,C.pf,C.tianqi,N.Status,N.reclaim,N.nc_e,N.exp,N.taskid,N.badnum,N.dog,N.decorative,N.activeItem,N.healthMode FROM  ' . getTName('qqfarm_config') . ' C Left JOIN ' . getTName('qqfarm_nc') . ' N ON N.uid=C.uid where C.uid=' . $uId);
}
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
if(!$list[0]['username']) {
	$list[0]['username'] = qf_getUserName($uId, true);//写入昵称
}

//健康模式
$isUpdate = 0;
$healthMode = qf_decode($list[0]['healthMode']);
if($_QFG['timestamp'] > $healthMode['endTime']) {
	$set = 0;
	$valid = 0;
	$canClose = 1;
	if($healthMode['valid'] != 0) {
		$isUpdate = 1;
		$healthMode['beginTime'] = 0;
		$healthMode['endTime'] = 0;
		$healthMode['set'] = 0;
		$healthMode['valid'] = 0;
		$healthMode['canClose'] = 1;
		$healthMode['date'] = '1970-01-01|1970-01-07';
	}
} elseif($_QFG['timestamp'] < $healthMode['beginTime'] && $healthMode['beginTime'] != 0) {
	$set = 1;
	$valid = 0;
	$canClose = 1;
} else {
	$set = 1;
	$valid = 1;
	$canClose = 0;
}
if($isUpdate == 1) {
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set healthMode='" . qf_encode($healthMode) . "' where uid=" . $uId);
	$isUpdate = 0;
}

//农田参数
$Status = qf_decode($list[0]['Status']);
foreach($Status as $key => $value) {
	//修复可能的错误
	if($key >= $list[0]['reclaim']) {
		unset($Status[$key]);
		$isUpdate = 1;
		continue;
	}
	//更新作物状态
	if($Status[$key]['a'] != 0) {
		$a = $Status[$key]['a'];
		$q = $Status[$key]['q'];
		$k = $Status[$key]['k'];
		$p = (array)$Status[$key]['p'];
		$zuowutime = $_QFG['timestamp'] - $q;
		if($zuowutime >= $cropstype[$a]['growthCycle'] && $k == 0 && $q != 0) {
			$b = 6;
			$c = 0;
			$d = 0;
			$e = 1;
			$f = 0;
			$g = 0;
			$h = 1;
			$k = $cropstype[$a]['output'];
			$cnt = 0; //
			foreach($p as $pk => $pv) {
				if($pv == 1 or $pv == 2) {
					$cnt += ceil(($_QFG['timestamp'] - $pk) / 300) + 1;
				} elseif($pv == 3) {
					$cnt += ceil(($_QFG['timestamp'] - $pk) / 300) * 2 + 2;
				}
			}
			if($cnt > 50) {
				$cnt = 50;
			}
			$k = ceil($k * (100 - $cnt) / 100);
			$l = floor($k * 0.6);
			$m = $k;
			$Status[$key]['b'] = $b;
			$Status[$key]['c'] = $c;
			$Status[$key]['d'] = $d;
			$Status[$key]['e'] = $e;
			$Status[$key]['f'] = $f;
			$Status[$key]['g'] = $g;
			$Status[$key]['h'] = $h;
			$Status[$key]['k'] = $k;
			$Status[$key]['l'] = $l;
			$Status[$key]['m'] = $m;
			$isUpdate = 1;
		}
	}
}
if($isUpdate == 1) {
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set Status='" . qf_encode(array_values($Status)) . "' where uid=" . $uId);
	$isUpdate = 0;
}

//装饰参数
$decorative = qf_decode($list[0]['decorative']);
foreach($decorative as $itemtype => $value) {
	foreach($value as $key => $value1) {
		if($value1['status'] == 1) {
			if($_QFG['timestamp'] < $value1['validtime'] || $value1['validtime'] == 1) {
				$decorative_echo[$itemtype]['itemId'] = $key;
			} else {
				unset($decorative[$itemtype][$key]);
				$isUpdate = 1;
				$decorative[$itemtype][$itemtype]['status'] = 1;
				$decorative_echo[$itemtype]['itemId'] = $itemtype;
			}
		} else {
			if($value1['validtime'] != 1 && $_QFG['timestamp'] >= $value1['validtime']) {
				unset($decorative[$itemtype][$key]);
				$isUpdate = 1;
			}
		}
	}
}
if($isUpdate == 1) {
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set decorative='" . qf_encode($decorative) . "' where uid=" . $uId);
	$isUpdate = 0;
}

//狗狗参数
$dog = qf_decode($list[0]['dog']);
$dogstr['dogId'] = 0;
$dogstr['isHungry'] = 0;
if($_QFG['timestamp'] > $dog['dogFeedTime']) {
	$dogstr['isHungry'] = 1;
} else {
	$dogstr['isHungry'] = 0;
}
if($dog) {
	foreach((array)$dog['dogs'] as $key => $value) {
		if($value['status'] == 1) {
			$decorative_echo['8']['itemId'] = 80000 + $key;
			$dogstr['dogId'] = $key;
		}
	}
}

//广告牌
if($list[0]['activeItem'] > 0) {
	$decorative_echo['9']['itemId'] = $list[0]['activeItem'];
}

//新手任务
$taskid = '';
if($list[0][taskid] < 12) {
	$taskid = ',"task":{"taskId":' . $list[0][taskid] . ',"taskFlag":1}';
}
if($list[0][taskid] == 0) {
	$taskid = ',"task":{"taskId":1,"taskFlag":1},"welcome":1';

}

//返回信息
if($toFriend) {
	echo '{"a":0,"c":0,"dog":' . qf_getEchoCode($dogstr) . ',"exp":' . $list[0]['exp'] . ',"farmlandStatus":' . qf_getEchoCode($Status) . ',"items":' . qf_getEchoCode($decorative_echo) . ',"user":{"healthMode":{"beginTime":' . $healthMode['beginTime'] . ',"canClose":' . $canClose . ',"date":"' . $healthMode['date'] . '","endTime":' . $healthMode['endTime'] . ',"serverTime":' . $_QFG['timestamp'] . ',"set":' . $set . ',"time":"' . $healthMode['time'] . '","valid":' . $valid . '},"pf":' . $list[0]['pf'] . '}}';
} else {
	//使坏次数
	$canbad = $list[0]['badnum'];
	//消费提示
	$isread = $_QFG['db']->result($_QFG['db']->query('SELECT COUNT(*) FROM ' . getTName('qqfarm_nclogs') . ' WHERE uid = ' . $_QFG['uid'] . ' and isread = 0'), 0);
	$a = $isread ? 1 : 0;
	//留言提示
	$isread = $_QFG['db']->result($_QFG['db']->query('SELECT COUNT(*) FROM ' . getTName('qqfarm_message') . ' WHERE toID = ' . $_QFG['uid'] . ' and isread = 0'), 0);
	$c = $isread ? 1 : 0;
	//天气
	$tqq = '雨天';
	if($list[0][tianqi] == 1) {
		$tqq = '晴天';
	}
	//VIP状态
	$vip = qf_decode($list[0]['vip']);
	//NPC任务参数
	if($_QSC['missionName']) {
		include_once("source/nc/mission/{$_QSC['missionName']}_vars.php");
	}
	$missionTime =$mission['PrepareTime'] ? strtotime($mission['PrepareTime']) : $_QFG['timestamp'];
	//输出信息
	echo '{"a":' . $a . ',"b":1,"c":' . $c . ',"cacheControl":{"diy":3,"seed":11,"tool":1},"d":' . (int)$vip['rsign'] . ',"dog":' . qf_getEchoCode($dogstr) . ',"e":' . $list[0]['nc_e'] . ',"exp":' . $list[0]['exp'] . ',"farmlandStatus":' . qf_getEchoCode($Status) . ',"items":' . qf_getEchoCode($decorative_echo) . ',"serverTime":{"time":' . $_QFG['timestamp'] . '}' . $taskid . ',"user":{"canbad":' . $canbad . ',"exp":' . $list[0]['exp'] . ',"headPic":"' . qf_getheadPic(0, 'small') . '","healthMode":{"beginTime":' . $healthMode['beginTime'] . ',"canClose":' . $canClose . ',"date":"' . $healthMode['date'] . '","endTime":' . $healthMode['endTime'] . ',"serverTime":' . $_QFG['timestamp'] . ',"set":' . $set . ',"time":"' . $healthMode['time'] . '","valid":' . $valid . '},"missionTime":' . $missionTime . ',"money":' . $list[0]['money'] . ',"pf":1,"uId":' . $_QFG['uid'] . ',"userName":"' . $list[0]['username'] . '","yellowlevel":' . qf_toVipLevel($vip['exp']) . ',"yellowstatus":' . (int)$vip['status'] . '},"weather":{"weatherDesc":"' . $tqq . '","weatherId":' . $list[0]['tianqi'] . '}}';

}

?>