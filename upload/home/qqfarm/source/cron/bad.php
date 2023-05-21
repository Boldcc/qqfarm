<?php

# 农场刷草/虫子/大便/蚊子
# Modify by seaif@zealv.com


//农场刷草和虫子
include_once("source/nc/config/cropstime.php");
$query = $_QFG['db']->query('SELECT uid,status FROM ' . getTName('qqfarm_nc'));
$query1 = $_QFG['db']->query('SELECT tianqi FROM ' . getTName('qqfarm_config'));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
while($value1 = $_QFG['db']->fetch_array($query1)) {
	$list1[] = $value1;
}
foreach($list as $ncList) {
	$farm = qf_decode($ncList['status']);
	foreach($farm as $key =>$value) {
		if(($_QFG['timestamp'] - $value['q']) < $cropstime[$value['a']][4]) {
			if($value['f'] == 0) {
				$suiji = mt_rand(0, 20);
				if($suiji < 10) {
					if($suiji < 4) {
						if($suiji < 2) {
							$value['f'] = 3;
							$fp = array($_QFG['timestamp'] => 2, ($_QFG['timestamp'] + 1) => 2, ($_QFG['timestamp'] + 2) => 2);
						} else {
							$value['f'] = 2;
							$fp = array($_QFG['timestamp'] => 2, ($_QFG['timestamp'] + 1) => 2);
						}
					} else {
						$value['f'] = 1;
						$fp = array($_QFG['timestamp'] => 2);
					}
				}
			}
			if($value['g'] == 0 && ($_QFG['timestamp'] - $value['q']) > $cropstime[$value['a']][2]) {
				$suiji = mt_rand(0, 20);
				if($suiji < 10) {
					if($suiji < 4) {
						if($suiji < 2) {
							$value['g'] = 3;
							$gp = array(($_QFG['timestamp'] + 10) => 1, ($_QFG['timestamp'] + 11) => 1, ($_QFG['timestamp'] + 12) => 1);
						} else {
							$value['g'] = 2;
							$gp = array(($_QFG['timestamp'] + 10) => 1, ($_QFG['timestamp'] + 11) => 1);
						}
					} else {
						$value['g'] = 1;
						$gp = array(($_QFG['timestamp'] + 10) => 1);
					}
				}
			}
			if(($_QFG['timestamp'] - $value['q']) < $cropstime[$value['a']][4]) {
				$suiji = mt_rand(0, 50);
				if($list1[0]['tianqi'] == 1) {
					if($value['h'] == 1) {
						if($suiji < 7) {
							$value['h'] = 0;
							$hp = array(($_QFG['timestamp'] + 20) => 3);
						}
					}
				}
			}
			$farm_p = array();
			if($fp) {
				$farm_p = $farm_p + $fp;
			}
			if($gp) {
				$farm_p = $farm_p + $gp;
			}
			if($hp) {
				$farm_p = $farm_p + $hp;
			}
			unset($fp);
			unset($gp);
			unset($hp);
			if($farm_p) {
				$value['p'] = $farm_p;
			}
		}
		$farm[$key] = $value;//回写参数
	}
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set status='" . qf_encode(array_values($farm)) . "' where uid=" . $ncList['uid']);
}


//牧场大便
$suiji = rand(1, 2);
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set dabian=dabian+" . $suiji . " where dabian<16");


//牧场蚊子
$query = $_QFG['db']->query("select bad from " . getTName('qqfarm_mc'));
while($value = $_QFG['db']->fetch_array($query)) {
	if($value[bad] == '') {
		$add_bad = '0,0';
	} else {
		$bad_array = explode(',', $value['bad']);
		$bad_array_count = count($bad_array);
		if($bad_array_count < 8) {
			$add_bad = $value[bad] . ',0';
		} else {
			exit();
		}
	}
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set bad='" . $add_bad . "'");
}

?>