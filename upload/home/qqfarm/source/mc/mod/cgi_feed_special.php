<?php

# 萝卜饲养

$uId = $_REQUEST['uId'] ? (int)$_REQUEST['uId'] : $_QFG['uid'];
$serial = $_REQUEST['serial'];

$fruit = $_QFG['db']->result($_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$fruit = qf_decode($fruit);
$luoboid = 3;
$fruit[$luoboid] = $fruit[$luoboid] - 1;

$query = $_QFG['db']->query("SELECT Status,sfeedleft FROM " . getTName("qqfarm_mc") . " where uid=" . $uId);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$animal = qf_decode($list[0][Status]);
$sfeedleft = $list[0][sfeedleft];

if($sfeedleft == 0) {
	die('{"errorContent":"当前牧场今天已被喂30个特殊作物，明天再来","errorType":"1001","serial":' . $serial . ',"sfeedleft":' . $sfeedleft . '}');
}

$animal[$serial]['growtime'] += 300;


foreach($animal as $key => $value) {
	if($key == $serial) {
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
		$newanimal = '{"animal":{"buyTime":'.$value['buyTime'].',"cId":'.$value['cId'].',"growTime":'.$value['growtime'].',"growTimeNext":'.$growTimeNext.',"hungry":0,"serial":'.$_REQUEST['serial'].',"status":'.$status.',"statusNext":'.$statusNext.',"totalCome":'.$totalCome.'},"serial":'.$_REQUEST['serial'].',"sfeedleft":'.$list[0]['sfeedleft'].'}';
		$animal[$key] = $value;//更新参数
	}
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',sfeedleft='" . ($sfeedleft-1) . "' where uid=" . $uId);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit='" . qf_encode($fruit) . "' where uid=" . $_QFG['uid']);

echo $newanimal;

?>