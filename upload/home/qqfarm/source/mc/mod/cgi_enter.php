<?php

# 牧场初始化

$uId = (int)$_REQUEST['uId'] > 0 ? (int)$_REQUEST['uId'] : $_QFG['uid'];

$query = $_QFG['db']->query('SELECT C.uid,C.username,C.money,C.vip,M.Status,M.exp,M.taskid,M.feed,M.decorative,M.bad,M.parade,M.dabian FROM ' . getTName('qqfarm_config') . ' C Left JOIN ' . getTName('qqfarm_mc') . ' M ON M.uid=C.uid where C.uid=' . $uId);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$parade = qf_decode($list[0]['parade']);
$animal = qf_decode($list[0]['Status']);
$feed = qf_decode($list[0]['feed']);
$decorative = qf_decode($list[0]['decorative']);
$touarr = array();

//便便
$dabian_mynum = 0;
$dabian_num = $list[0][dabian];
//bad
$bad_num = 0;
$bad_mynum = 0;
if($list[0][bad] != '') {
	$bad = explode(',', $list[0][bad]);
	$bad_num = count($bad);
	if($uId != $_QFG['uid']) {
		for($i = 0; $i < $bad_num; $i++) {
			if($bad[$i] == $_QFG['uid']) {
				$bad_mynum = $bad_mynum + 1;
			}
		}
		if($bad_mynum > 8) {
			$bad_mynum = 8;
		}
	}
}
//bad

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
		if($uId != $_QFG['uid']) {
			if($value['totalCome'] > $animaltype[$value['cId']]['output'] / 2) {
				$touID = explode(',',$value['tou']);
				if(in_array($_QFG['uid'], $touID)) {
					if($touarr[$value['cId']] != 3) {
						$touarr[$value['cId']] = 2;
					}
				} else {
					$touarr[$value['cId']] = 3;
				}
			} elseif($value['totalCome']>0 || $value['totalCome']<=$animaltype[$value['cId']]['output'] / 2) {
				if($touarr[$value['cId']] != 3) {
					$touarr[$value['cId']] = 1;
				}
			} else {
				if($touarr[$value['cId']] != 3) {
					$touarr[$value['cId']] = 0;
				}
			}
		} else {		
				$touarr[$value['cId']] = 3;
		}
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
		$growTimeNext = $growTimeNext > 0 ? $growTimeNext : 0;
		// _xieph
		$newanimal[] = array('buyTime'=>$value['buyTime'],'cId'=>$value['cId'],'growTime'=>$value['growtime'],'growTimeNext'=>$growTimeNext,'hungry'=>$hungry,'serial'=>$key,'status'=>$status,'statusNext'=>$statusNext,'totalCome'=>$totalCome);
		$animal[$key] = $value;//更新参数
		
	}
}
$newanimal = str_replace('null', '[]', qf_getEchoCode($newanimal));


//更新用户数据
$feed['animalfeedtime'] = $_QFG['timestamp'];
$feed['animalfood'] = ceil($feed['animalfood']);
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set Status='" . qf_encode(array_values($animal)) . "',feed='".qf_encode($feed)."' where uid=" . $uId);

//新手任务
$taskFlag = $list[0]['taskid'] == 10 ? 0 : 1;

//牧场房子
$decorative[item2] = '"2":{"id":102,"lv":' . $decorative[item2] . '},';
if($decorative[item3] == 0) {
	$decorative[item3] = '';
} else {
	$decorative[item3] = '"3":{"id":103,"lv":' . $decorative[item3] . '},';
}
//xieph
foreach($touarr as $tk=>$tv) { //动物收获,偷状态提示
	if($stealflag) {
		$stealflag .= ',"'.$tk.'":'.$tv;
	} else {
		$stealflag = '"'.$tk.'":'.$tv;
	}
}
//_xieph
if($uId != $_QFG['uid']) {
	//输出信息
	echo stripslashes('{"animal":' . $newanimal . ',"animalFood":' . $feed[animalfood] . ',"badinfo":[{"mynum":' . $bad_mynum . ',"num":' . $bad_num . ',"type":1},{"mynum":' . $dabian_mynum . ',"num":' . $dabian_num . ',"type":2}],"c":0,"items":{"1":{"id":101,"lv":' . $decorative[item1] . '},' . $decorative[item2] . $decorative[item3] . '"4":{"id":104,"lv":' . $decorative[item4] . '}},"stealflag":{'.$stealflag.'},"parade":' . qf_getEchoCode($parade) . ',"task":{"taskFlag":' . $taskFlag .
		',"taskId":' . $list[0][taskid] . '},"user":{"exp":' . $list[0][exp] . ',"money":' . $list[0][money] . ',"uId":' . $uId . '}}');
} else {

	//消费提示
	$isread = $_QFG['db']->result($_QFG['db']->query('SELECT COUNT(*) FROM ' . getTName('qqfarm_mclogs') . ' WHERE uid=' . $uId . ' and isread = 0'), 0);
	$a = $isread ? 1 : 0;
	//留言提示
	$isread = $_QFG['db']->result($_QFG['db']->query('SELECT COUNT(*) FROM ' . getTName('qqfarm_message') . ' WHERE toID = ' . $_QFG['uid'] . ' and isread = 0'), 0);
	$c = $isread ? 1 : 0;
	//用户头像
	$image = qf_getheadPic(0, 'small');
	//VIP状态
	$vip = qf_decode($list[0]['vip']);
	//输出信息
	echo stripslashes('{"a":' . $a . ',"c":' . $c . ',"animal":' . $newanimal . ',"animalFood":' . $feed[animalfood] . ',"badinfo":[{"mynum":' . $bad_mynum . ',"num":' . $bad_num . ',"type":1},{"mynum":' . $dabian_mynum . ',"num":' . $dabian_num . ',"type":2}],"items":{"1":{"id":101,"lv":' . $decorative[item1] . '},' . $decorative[item2] . $decorative[item3] . '"4":{"id":104,"lv":' . $decorative[item4] . '}},"parade":' . qf_getEchoCode($parade) . ',"notice":"","serverTime":{"time":' . $_QFG['timestamp'] . '},"stealflag":{'.$stealflag.'},"task":{"taskFlag":' . $taskFlag . ',"taskId":' . $list[0][taskid] . '},"user":{"exp":' . $list[0][exp] . ',"headPic":"' . $image . '","money":' . $list[0][money] . ',"uId":' . $uId . ',"userName":"' . $list[0]['username'] . '","yellowlevel":' . qf_toVipLevel($vip['exp']) . ',"yellowstatus":' . (int)$vip['status'] . '},"weather":{"weatherDesc":"晴天","weatherId":1}}');
}
?>