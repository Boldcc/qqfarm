<?php

# 作物收获

include_once("source/nc/config/cropstime.php");

$query = $_QFG['db']->query("SELECT Status,fruit,package,tools,dog FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farmarr = qf_decode($list[0]['Status']);
$fruitarr = qf_decode($list[0]['fruit']);
$packagearr = qf_decode($list[0]['package']);
$toolsarr = qf_decode($list[0]['tools']);
$dogarr = qf_decode($list[0]['dog']);

$pieces = explode(",", $_REQUEST['place']);
foreach($pieces as $pid) {
	$cid = $farmarr[$pid]['a'];
	if($cid == 0) {
		exit();
	}
	if($farmarr[$pid]['b'] != 6) {
		exit();
	}
	if($farmarr[$pid]['j'] == 0 && $_QFG['timestamp'] - $farmarr[$pid]['q'] < $cropstime[$farmarr[$pid]['a']][4]) {
		exit();
	}
	if($farmarr[$pid]['j'] > 0 && $_QFG['timestamp'] - $farmarr[$pid]['q'] < $cropstime[$farmarr[$pid]['a']][4]) {
		exit();
	}
	$output = $farmarr[$pid]['m'];
	$fruitarr[$cid] = $fruitarr[$cid] + $output;
	$harvest = $farmarr[$pid]['m'];
	$farmarr[$pid]['c'] = 0;
	$farmarr[$pid]['d'] = 0;
	$farmarr[$pid]['e'] = 1;
	$farmarr[$pid]['f'] = 0;
	$farmarr[$pid]['g'] = 0;
	$farmarr[$pid]['h'] = 1;
	$farmarr[$pid]['i'] = 100;
	$farmarr[$pid]['k'] = 0;
	$farmarr[$pid]['l'] = 0;
	$farmarr[$pid]['m'] = 0;
	$farmarr[$pid]['n'] = array();
	$farmarr[$pid]['o'] = 0;
	$farmarr[$pid]['p'] = array();
	$farmarr[$pid]['q'] = 0;
	$farmarr[$pid]['r'] = $_QFG['timestamp'];
	if($farmarr[$pid]['j'] + 1 == $cropstype[$farmarr[$pid]['a']][maturingTime]) {
		$farmarr[$pid]['b'] = 7;
		$farmarr[$pid]['j'] = 0;
	} else {
		$farmarr[$pid]['b'] = 6;
		$farmarr[$pid]['j'] = $farmarr[$pid]['j'] + 1;
		$farmarr[$pid]['q'] = $_QFG['timestamp'] - $cropstime[$farmarr[$pid]['a']][2];
	}
	$exp_str = $exp_str + $cropstype[$farmarr[$pid]['a']]['cropExp'];
	//当前活动红包
	if($_QSC['missionName']) {
		include("source/nc/mission/{$_QSC['missionName']}_gift.php");
	}
	//升级提示
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp = exp+$exp_str where uid=" . $_QFG['uid']);
	$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup_arr = 'false';
	if($exp_arr >= $levelup && $levelup < 93001) {
		include("source/nc/config/levelup.php"); //升级提示
	}
	$echo_str[] = '{"code":1,"direction":"","levelUp":' . $levelup_arr . ',"exp":' . $cropstype[$farmarr[$pid]['a']][cropExp] . ',"farmlandIndex":' . $pid . ',' . $red_gift . '"harvest":' . $harvest . ',"poptype":4,"status":{"cId":' . $farmarr[$pid]['a'] . ',"cropStatus":' . $farmarr[$pid]['b'] . ',"fertilize":' . $farmarr[$pid]['o'] . ',"harvestTimes":' . $farmarr[$pid]['j'] . ',"oldweed":' . $farmarr[$pid]['c'] . ',"oldpest":' . $farmarr[$pid]['d'] . ',"oldhumidity":' . $farmarr[$pid]['e'] . ',"weed":' . $farmarr[$pid]['f'] . ',"pest":' . $farmarr[$pid]['g'] . ',"humidity":' . $farmarr[$pid]['h'] . ',"killer":' . qf_getEchoCode($farmarr[$pid]['i']) . ',"output":' . $farmarr[$pid]['k'] . ',"min":' . $farmarr[$pid]['l'] . ',"leavings":' . $farmarr[$pid]['m'] . ',"thief":{},"action":' . qf_getEchoCode($farmarr[$pid]['p']) . ',"plantTime":' . $farmarr[$pid]['q'] . ',"updateTime":' . $farmarr[$pid]['r'] . '}}';
	$repertory = $_QFG['db']->result($_QFG['db']->query("SELECT repertory FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$repertory = qf_decode($repertory);
	$flag = false;
	foreach((array)$repertory as $key => $val) {
		if($cid == $val['cId']) {
			$flag = true;
			$repertory[$key]['harvestNumber'] = $repertory[$key]['harvestNumber'] + $output;
		}
	}
	if(!$flag) {
		$cName = $cropstype[$cid]['cName'];
		$repertory[] = array("cId"=>$cid,"cName"=>$cName,"harvestNumber"=>$output,"scroungeNumber"=>0);
	}
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set repertory='" . qf_encode($repertory) . "' where uid=" . $_QFG['uid']);
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "',fruit='" . qf_encode($fruitarr) . "',package='" . qf_encode($packagearr) . "',tools='" . qf_encode($toolsarr) . "',dog='" . qf_encode($dogarr) . "' where uid=" . $_QFG['uid']);

echo '[' . implode(',', $echo_str) . ']';

qf_addFeed('farmlandstatus_harvest');

?>