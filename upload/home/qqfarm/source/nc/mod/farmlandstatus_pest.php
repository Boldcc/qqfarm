<?php

# 恶意放虫

//检查使坏次数
$farm_badnum = $_QFG['db']->result($_QFG['db']->query("SELECT badnum FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
if($farm_badnum <= 0) {
	echo'{"code":1,"direction":"您今天使坏的次数已达到50次","poptype":1}';
	exit();
}

$query = $_QFG['db']->query("SELECT Status,tips,pest FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farm_Status = qf_decode($list[0]['Status']);
$Tips = qf_decode($list[0]['tips']);
$farm_pest = qf_decode($list[0]['pest']);

$pieces = explode(",", $_REQUEST['place']);
foreach($pieces as $pid) {
	if($farm_badnum > 0) {
		$g = $farm_Status[$pid]['g'];
		$farm_p = $farm_Status[$pid]['p'];
		if($g < 3) {
			$code_temp = 1;
			$g += 1;
			$farm_pest[$pid][$g] = $_QFG['uid'];
			$farm_badnum -= 1;
			$echo_str[] = '{"canbad":' . $farm_badnum . ',"code":1,"direction":"' . $Tips['pest_a'] . '","farmlandIndex":' . $pid . ',"poptype":1,"pest":' . $g . '}';
		}
		$farm_Status[$pid]['g'] = $g;
		//农作物状态p
		if($farm_p) {
			$farm_time = $_QFG['timestamp'];
			if(isset($farm_p[$farm_time])) {
				$farm_time += $g;
			}
			$farm_p1 = array($farm_time => 1);
			$farm_p = $farm_p + $farm_p1;
		} else {
			$farm_p = array($_QFG['timestamp'] => 1);
		}
		$farm_Status[$pid]['p'] = $farm_p;
	}
}

//放虫日志
if($code_temp != 0) {
	$sql1 = "SELECT `id`, `uid`, `fromid`, `count`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type = 3 and uid = " . $_REQUEST['ownerId'];
	$result = $_QFG['db']->query($sql1);
	$result = $_QFG['db']->fetch_array($result);
	if($result != null) {
		$sql = "UPDATE " . getTName("qqfarm_nclogs") . " set time = " . $_QFG['timestamp'] . " where fromid = " . $_QFG['uid'] . " and type = 3 and uid = " . $_REQUEST['ownerId'];
	} else {
		$sql = "INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_REQUEST['ownerId'] . ", 3,0, " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
	}
	$_QFG['db']->query($sql);
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm_Status)) . "',pest='" . qf_encode($farm_pest) . "' where uid=" . intval($_REQUEST['ownerId']));
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set badnum=" . $farm_badnum . " where uid=" . $_QFG['uid']);

echo '' . implode(',', $echo_str) . '';

?>