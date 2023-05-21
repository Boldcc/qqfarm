<?php

# 放蚊子

$uId = intval($_REQUEST['uId']);
$num = intval($_REQUEST['num']);

$badnum = $_QFG['db']->result($_QFG['db']->query("SELECT badnum FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']), 0);
if($badnum > 24) {
	die('{"errorContent":"每天最多使坏25次","errorType":-2005}');
}
if($num + $badnum > 25) {
	$num = floor(25 - floor($badnum));
}

$badnum = 0;
$bad = $_QFG['db']->result($_QFG['db']->query('SELECT bad FROM ' . getTName('qqfarm_mc') . ' where uid=' . $uId), 0);
if($bad != '') {
	$badnum = count(explode(',', $bad));
}
if(($num + $badnum) >= 9) {
	$num = 8 - $badnum;
}

for($i = 0; $i < $num; $i++) {
	if($bad == '') {
		$bad = $_QFG['uid'];
	} else {
		$bad = $bad . ',' . $_QFG['uid'];
	}
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set bad = '" . $bad . "',badtime = " . $_QFG['timestamp'] . " where uid=" . $uId);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set badnum = badnum+'" . $num . "' where uid=" . $_QFG['uid']);

//放蚊子日志
$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . $uId . " AND type = 6 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	if(($value['type'] == 6) && ($value['fromid'] == $_QFG['uid']) && ($num > 0)) {
		$scount = $value['count'];
		$stime = $value['time'];
		$scount = $scount + $num;
		$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . $uId . " AND type = 6 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
	}
}
if((!$sql1) && ($num > 0)) {
	$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $uId . ", 6," . $num . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0, 0);";
}
if($sql1) $query = $_QFG['db']->query($sql1);

//返回状态
echo '{"cId":1,"leftnum":11,"num":' . $num . ',"total":' . ($badnum + $num) . '}';

?>