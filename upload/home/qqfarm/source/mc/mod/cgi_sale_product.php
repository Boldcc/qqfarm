<?php

# 卖出产品

$package = $_QFG['db']->result($_QFG['db']->query('SELECT package FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);
$mclock = $_QFG['db']->result($_QFG['db']->query('SELECT mclock FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);
$package = qf_decode($package);
$money = 0;
$mclock = explode(',',$mclock);

if($_REQUEST['saleAll'] == '1') {
	foreach($package as $key => $value) {
			if( !in_array($key, $mclock) && $value > 0 ) {
				$iid .= $iid==null ? $key : ",".$key;
				$count .= $count==null ? $value : ",".$value;
				$money += $animalname[$key]['price'] * $value;
				unset($package[$key]);
			}	
	}
	$echo_str = '{"direction":"操作成功，共获得收入和感谢金 <font color=\"#FF6600\"><b>' . $money . '</b> </font>金币","money":' . $money . '}';
	$query = $_QFG['db']->query("INSERT INTO " . getTName("qqfarm_mclogs") ." (`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money` ) VALUES (" .	$_QFG['uid'] . ", 9,'" . $count . "', " . $_QFG['uid'] .", " . $_QFG['timestamp'] . ", '" . $iid. "', 1, " . $money . ");");

} else {
	if($package[$_REQUEST['cId']] < $_REQUEST['num']) {
		exit();
	}
	$money = $animalname[$_REQUEST['cId']]['price'] * $_REQUEST['num'];
	$package[$_REQUEST['cId']] = $package[$_REQUEST['cId']] - $_REQUEST['num'];
	foreach($package as $key => $value) {
		if($value == 0) unset($package[$key]);
	}
	$echo_str = '{"cId":' . $_REQUEST['cId'] . ',"direction":"成功卖出 <font color=\"#0099FF\"><b>' . $_REQUEST['num'] . '</b></font> 个' . $animalname[$_REQUEST['cId']]['name'] . '，赚到 <font color=\"#FF6600\"><b>' . $money . '</b></font> 金币","money":' . $money . '}';
	//出售日志
	$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " .intval($_QFG['uid']) . " AND type = 9 AND time > " . ($_QFG['timestamp'] -3600) . " AND fromid =" . $_QFG['uid'];
	$query = $_QFG['db']->query($sql);
	while ($value = $_QFG['db']->fetch_array($query)) {
		if (($value['type'] == 9) && ($value['fromid'] == $_QFG['uid']) && ($_REQUEST['num'] > 0)) {
			$list = $value['iid'];
			$moneyt = $value['money'];
			$scount = $value['count'];
			$stime = $value['time'];
			$iid_arr = explode(',',$value['iid']);
			$count_arr = explode(',',$value['count']);
			if(!in_array($_REQUEST['cId'],$iid_arr)) {
				$list = $list . "," . $_REQUEST['cId'];
				$scount = $scount . "," . $_REQUEST['num'];
			} else {
				$scount = $scount + $_REQUEST['num'];
			}
			

			$moneyt = $moneyt + ($animalname[$_REQUEST['cId']][price] * $_REQUEST['num']);
			$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set iid = '" . $list ."', money = '" . $moneyt . "', count ='" . $scount . "', time = " . $_QFG['timestamp'] .", isread = 1 where uid = " . intval($_QFG['uid']) ." AND type = 9 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" .$_QFG['uid'];

		}
	}
	if ((!$sql1) && ($_REQUEST['num'] > 0)) {
		$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") ." (`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money` ) VALUES (" .	$_QFG['uid'] . ", 9," . $_REQUEST['num'] . ", " . $_QFG['uid'] .", " . $_QFG['timestamp'] . ", " . $_REQUEST['cId'] . ", 1, " . $animalname[$_REQUEST['cId']][price] *$_REQUEST['num'] . ");";	
	}
	$query = $_QFG['db']->query($sql1);
}
$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+' . $money . ' where uid=' . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set package='" . qf_encode($package) . "' where uid=" . $_QFG['uid']);



echo $echo_str;

?>