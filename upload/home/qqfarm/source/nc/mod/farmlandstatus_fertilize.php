<?php

# 作物加肥
if(intval($_REQUEST['ownerId']) == $_QFG['uid']) {
	include_once("source/nc/config/cropstime.php");
	include_once("source/nc/config/toolstype.php");
	$query = $_QFG['db']->query("SELECT tools,Status FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$farmarr = qf_decode($list[0]['Status']);
	$fertarr = qf_decode($list[0]['tools']);
	if($fertarr[$_REQUEST['tId']] == 0) {
		exit();
	}
	$zuowutime = $_QFG['timestamp'] - $farmarr[$_REQUEST['place']]['q'];
	$ii = 0;
	foreach($cropstime[$farmarr[$_REQUEST['place']]['a']] as $key => $value) {
		if($value <= $zuowutime) {
			$ii = $key + 1;
		}
	}
	if($farmarr[$_REQUEST['place']]['o'] == $ii + 1) {
		exit();
	}
	$zuowutime += $Toolstype[30000 + $_REQUEST['tId']][effect];

	if($cropstime[$farmarr[$_REQUEST['place']]['a']][$ii] < $zuowutime) {
		$zuowutime = $cropstime[$farmarr[$_REQUEST['place']]['a']][$ii];
	}
	$farmarr[$_REQUEST['place']]['q'] = $_QFG['timestamp'] - $zuowutime;
	$farmarr[$_REQUEST['place']]['o'] = $ii + 1;
	$a = $farmarr[$_REQUEST['place']]['a'];
	if($zuowutime >= $cropstype[$a][growthCycle]) {
		$farmarr[$_REQUEST['place']]['b'] = 6;
		$farmarr[$_REQUEST['place']]['c'] = 0;
		$farmarr[$_REQUEST['place']]['d'] = 0;
		$farmarr[$_REQUEST['place']]['e'] = 1;
		$farmarr[$_REQUEST['place']]['f'] = 0;
		$farmarr[$_REQUEST['place']]['g'] = 0;
		$farmarr[$_REQUEST['place']]['h'] = 1;
		$farmarr[$_REQUEST['place']]['k'] = $cropstype[$a][output];
		$farmarr[$_REQUEST['place']]['l'] = floor($cropstype[$a][output] * 0.6);
		$farmarr[$_REQUEST['place']]['m'] = $cropstype[$a][output];
	}

	$fertarr[$_REQUEST['tId']] = $fertarr[$_REQUEST['tId']] - 1;
	foreach($fertarr as $key => $value) {
		if($value == 0) {
			unset($fertarr[$key]);
		}
	}

	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "',tools='" . qf_encode($fertarr) . "' where uid=" . $_QFG['uid']);

	$cId = $farmarr[$_REQUEST['place']]['a'] ;
	$cropStatus = $farmarr[$_REQUEST['place']]['b'] ;
	$oldweed = $farmarr[$_REQUEST['place']]['c'] ;
	$oldpest = $farmarr[$_REQUEST['place']]['d'] ;
	$oldhumidity = $farmarr[$_REQUEST['place']]['e'] ;
	$weed = $farmarr[$_REQUEST['place']]['f'] ;
	$pest = $farmarr[$_REQUEST['place']]['g'] ;
	$humidity = $farmarr[$_REQUEST['place']]['h'] ;
	$health = $farmarr[$_REQUEST['place']]['i'] ;
	$harvestTimes = $farmarr[$_REQUEST['place']]['j'] ;
	$output = $farmarr[$_REQUEST['place']]['k'] ;
	$min = $farmarr[$_REQUEST['place']]['l'] ;
	$leavings = $farmarr[$_REQUEST['place']]['m'] ;
	$thief = qf_getEchoCode($farmarr[$_REQUEST['place']]['n']) ;
	$fertilize = $farmarr[$_REQUEST['place']]['o'] ;
	$action = qf_getEchoCode($farmarr[$_REQUEST['place']]['p']) ;
	$plantTime = $farmarr[$_REQUEST['place']]['q'] ;
	$updateTime = $farmarr[$_REQUEST['place']]['r'] ;

	echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"code":1,"tId":' . $_REQUEST['tId'] . ',"status":{"cId":' . $cId . ',"cropStatus":' . $cropStatus . ',"oldweed":' . $oldweed . ',"oldpest":' . $oldpest . ',"oldhumidity":' . $oldhumidity . ',"weed":' . $weed . ',"pest":' . $pest . ',"humidity":' . $humidity . ',"health":' . $health . ',"harvestTimes":' . $harvestTimes . ',"output":' . $output . ',"min":' . $min . ',"leavings":' . $leavings . ',"thief":"' . $thief .
		'","fertilize":' . $fertilize . ',"action":' . $action . ',"plantTime":' . $plantTime . ',"updateTime":' . $updateTime . '}}';

	qf_addFeed('farmlandstatus_fertilize');
}

?>