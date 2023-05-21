<?php

# 健康模式

$query = $_QFG['db']->query("SELECT healthMode FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$healthMode = qf_decode($list[0]['healthMode']);
if($healthMode["set"] == 1) {
	$set = $healthMode["canClose"] = 1;
	$set = $healthMode["set"] = 0;
	$set = $healthMode["valid"] = 0;
	$set = $healthMode["beginTime"] = 0;
	$set = $healthMode["endTime"] = 0;
	$set = $healthMode["serverTime"] = 0;
	$set = $healthMode["time"] = "08|00";
	$set = $healthMode["date"] = "1970-01-01|1970-01-07";
} else {
	$set = $healthMode["set"] = 1;
	$set = $healthMode["valid"] = 1;
	$set = $healthMode["beginTime"] = strtotime('1 day');
	$set = $healthMode["endTime"] = strtotime('8 day');
	$set = $healthMode["canClose"] = 0;
	$set = $healthMode["serverTime"] = strtotime('1 day');
	$time = $healthMode["time"] = $_REQUEST['time'];
	$datetime = date('Y-m-d', strtotime('1 day'));
	$dtak = date("Y-m-d", strtotime('8 day'));
	$date = $healthMode["date"] = "" . $datetime . "|" . $dtak . "";
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set healthMode='" . qf_encode($healthMode) . "' where uid=" . $_QFG['uid']);

echo '{"code":1,"date":"' . $date . '"}';

?>