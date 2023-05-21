<?php

# 恶意种草

//检查使坏次数
$farm_badnum = $_QFG['db']->result($_QFG['db']->query("SELECT badnum FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
if($farm_badnum <= 0) {
	echo'{"code":1,"direction":"您今天使坏的次数已达到50次","poptype":1}';
	exit();
}

$query = $_QFG['db']->query("SELECT Status,tips,weed FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId']));

while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$farm_Status = qf_decode($list[0]['Status']);
$Tips = qf_decode($list[0]['tips']);
$farm_weed = qf_decode($list[0]['weed']);

$pieces = explode(",", $_REQUEST['place']);
foreach($pieces as $key => $value) {
	if($farm_badnum > 0) {
		$f = $farm_Status[$value]['f'];
		$farm_w = $farm_Status[$value]['p'];
		if($f < 3) {
			$code_temp = 1;
			$f += 1;
			$farm_weed[$value][$f] = $_QFG['uid'];
			$farm_badnum -= 1;
			$echo_str[] = '{"canbad":' . $farm_badnum . ',"code":1,"direction":"' . $Tips['weed_a'] . '","farmlandIndex":' . $value . ',"poptype":1,"weed":' . $f . '}';
		}
		$farm_Status[$value]['f'] = $f;
		//农作物状态p
		if($farm_w) {
			$farm_time = $_QFG['timestamp'];
			if(isset($farm_w[$farm_time])) {
				$farm_time += $f;
			}
			$farm_w1 = array($farm_time => 2);
			$farm_w = $farm_w + $farm_w1;
		} else {
			$farm_w = array($_QFG['timestamp'] => 2);
		}
		$farm_Status[$value]['p'] = $farm_w;
	} else break;
}

//放草日志
if($code_temp != 0) {
	$sql1 = "SELECT `id`, `uid`, `fromid`, `count`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type = 5 and uid = " . $_REQUEST['ownerId'];
	$result = $_QFG['db']->query($sql1);
	$result = $_QFG['db']->fetch_array($result);
	if($result != null) {
		$sql = "UPDATE " . getTName("qqfarm_nclogs") . " set time = " . $_QFG['timestamp'] . " where fromid = " . $_QFG['uid'] . " and type = 5 and uid = " . $_REQUEST['ownerId'];
	} else {
		$sql = "INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_REQUEST['ownerId'] . ", 5,0, " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
	}
	$_QFG['db']->query($sql);
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm_Status)) . "',weed='" . qf_encode($farm_weed) . "' where uid=" . intval($_REQUEST['ownerId']));
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set badnum=" . $farm_badnum . " where uid=" . $_QFG['uid']);

echo '' . implode(',', $echo_str) . '';

?>