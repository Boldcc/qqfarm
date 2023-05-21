<?php

# 好友状态

include_once("source/nc/config/cropstime.php");

//好友条件
$condition = ' limit 0,1000';
if($_QSC['friendType'] == 1) {
	$friends = qf_getFriends(0);
	$friends .= (empty($friends) ? '' : ',') . $_QFG['uid'];
	$condition = " WHERE uid IN({$friends})" . $condition;
}

$query = $_QFG['db']->query("SELECT uid,Status FROM " . getTName("qqfarm_nc") . $condition);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

foreach($list as $value) {
	$farm_Status = qf_decode($value[Status]);
	foreach($farm_Status as $value_1) {
		$cId = intval($value_1['a']);
		if($cId > 0) {
			$time1 = $cropstime[$cId][4];
			$time3 = $_QFG['timestamp'] - $value_1['q'];
			$flag = $time3 >= $time1;
			if($flag && $value_1['q'] > 0) {
				if($value_1['m'] == 0) {
					$temp = $value_1['q'] + $time1;
					$n = $value_1['n'];
					if(!isset($n[$_QFG['uid']])) {
						$exp[$value['uid']]["1"] = $temp;
					}
				} else {
					if($value_1['m'] > $value_1['l']) {
						$temp = $value_1['q'] + $time1;
						$n = $value_1['n'];
						if(!isset($n[$_QFG['uid']])) {
							$exp[$value['uid']]["1"] = $temp;
						}
					}
				}
			} elseif(!$flag && $value_1['a'] != '0') {
				if($value_1['f'] > 0) {
					$exp[$value['uid']]["2"] = 1;
				}
				if($value_1['g'] > 0) {
					$exp[$value['uid']]["3"] = 1;
				}
			}
		}
	}
}

$exp = qf_getEchoCode($exp);
$int = strlen($exp);
$str = substr($exp, $int - 1, 1);
if($str == ",") {
	$exp = substr($exp, 0, $int - 1);
}
echo '{"status":' . $exp . '}';

?>