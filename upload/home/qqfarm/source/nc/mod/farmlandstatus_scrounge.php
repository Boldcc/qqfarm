<?php

# 偷取作物
$pieces = explode(",", $_REQUEST['place']);
$query = $_QFG['db']->query("SELECT Status,dog FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$query = $_QFG['db']->query("SELECT Status,fruit,repertory FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farm_Status = qf_decode($list[0][Status]);
$farm_dog = qf_decode($list[0][dog]);
$Status = qf_decode($list[1][Status]);
$farm_fruit = qf_decode($list[1][fruit]);
$farm_repertory = qf_decode($list[1][repertory]);



$msg_temp = array();
$c_n = 0; //偷走的个数

foreach($pieces as $id) {
	$Status_h = 0;
	foreach($Status as $Status_k => $Status_v) {
			if(intval($Status_v['a']) > 0) {
				$Status_h++;
			}
	}
	if($Status_h == 0) {
		die('{"code":1,"farmlandIndex":' . $id . ',"direction":"　您都没种菜，　就想来偷菜！","poptype":1}');
	}
	$a = $farm_Status[$id]['a'] ;
	$b = $farm_Status[$id]['b'] ;
	$c = $farm_Status[$id]['c'] ;
	$d = $farm_Status[$id]['d'] ;
	$e = $farm_Status[$id]['e'] ;
	$f = $farm_Status[$id]['f'] ;
	$g = $farm_Status[$id]['g'] ;
	$h = $farm_Status[$id]['h'] ;
	$i = $farm_Status[$id]['i'] ;
	$j = $farm_Status[$id]['j'] ;
	$k = $farm_Status[$id]['k'] ;
	$l = $farm_Status[$id]['l'] ;
	$m = $farm_Status[$id]['m'] ;
	$n = $farm_Status[$id]['n'] ;
	$o = $farm_Status[$id]['o'] ;
	$p = $farm_Status[$id]['p'] ;
	$q = $farm_Status[$id]['q'] ;
	$r = $farm_Status[$id]['r'] ;
	$n_temp = array_flip($n);
	if(in_array($_QFG['uid'], $n_temp)) {
		die('{"errorContent":"unknow act","errorType":"act"}');
	} else {
		//如果主人养狗且狗粮尚有，随机被狗咬@_@
		if($_QFG['timestamp'] < $farm_dog['dogFeedTime']) {
			foreach($farm_dog['dogs'] as $key => $value) {
				if($value['status'] == 1) {
					$int1_temp = rand(1, 10);
					if($int1_temp > 10 - $key) {
						$dog_money = $cropstype[$a]['sale'];
						$int2_temp = rand(1, 10);
						if($int2_temp > 8) {
							$dog_money = $dog_money + round(4 * rand(1, 20));
						} else {
							$dog_money = $dog_money + round(2 * rand(1, 10)) * rand(1, 2);
						}
						$n[$_QFG['uid']] = 0;
						$dog_str = "你在偷窃过程中被TA的狗狗发现，在逃跑过程中丢失" . $dog_money . "金币。";
						$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money = money + " . $dog_money . " where uid=" . intval($_REQUEST['ownerId']));
						$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money = money - " . $dog_money . " where uid=" . $_QFG['uid']);
					} else {
						$dog_str = '';
					}
				}
			}
		}
		//被狗咬过~~
		if($dog_str) {
			//$n[$_QFG['uid']]=0;
			if(array_key_exists(0, $msg_temp)) {
				$msg_temp[0] += $dog_money;
			} else {
				$msg_temp[0] = $dog_money;
			}
			$echo_str[] = '{"code":1,"direction":"' . $dog_str . '","farmlandIndex":' . $id . ',"harvest":0,"poptype":3,"money":-' . $dog_money . '}';
		}
		//未被狗咬^_^
		else {
			$rand_number = rand(1, 100);
			if($rand_number > 0 and $rand_number <= 50) {
				$n[$_QFG['uid']] = 1;
			} else
				if($rand_number > 50 and $rand_number <= 70) {
					$n[$_QFG['uid']] = 2;
				} else
					if($rand_number > 70 and $rand_number <= 80) {
						$n[$_QFG['uid']] = 3;
					} else
						if($rand_number > 80 and $rand_number <= 95) {
							$n[$_QFG['uid']] = 4;
						} else {
							$n[$_QFG['uid']] = 5;
						}
						$n[$_QFG['uid']] = min(($m - $l), $n[$_QFG['uid']]); //计算最大可偷数
			$farm_fruit[$a] += $n[$_QFG['uid']];
			$m -= $n[$_QFG['uid']];
			//作物剩下产量小于最低产量或未偷取，跳过~
			if($m < $l || $n[$_QFG['uid']] == 0) {
				continue;
			}
			if(array_key_exists($a, $msg_temp)) {
				$msg_temp[$a] += 1;
			} else {
				$msg_temp[$a] = 1;
			}
			$echo_str[] = '{"code":1,"direction":"' . $dog_str . '","farmlandIndex":' . $id . ',"harvest":'.$n[$_QFG['uid']].',"poptype":4,"status":{"action":' . qf_getEchoCode($p) . ',"cId":' . $a . ',"cropStatus":' . $b . ',"fertilize":' . $o . ',"harvestTimes":' . $j . ',"health":' . $i . ',"humidity":' . $h . ',"leavings":' . $m . ',"min":' . $l . ',"oldhumidity":' . $e . ',"oldpest":' . $d . ',"oldweed":' . $c . ',"output":' . $k . ',"pest":' . $g . ',"plantTime":' .
				$q . ',"thief":' . qf_getEchoCode($n) . ',"updateTime":' . $r . ',"weed":' . $f . '}}';
		}
		$farm_Status[$id]['a'] = $a;
		$farm_Status[$id]['b'] = $b;
		$farm_Status[$id]['c'] = $c;
		$farm_Status[$id]['d'] = $d;
		$farm_Status[$id]['e'] = $e;
		$farm_Status[$id]['f'] = $f;
		$farm_Status[$id]['g'] = $g;
		$farm_Status[$id]['h'] = $h;
		$farm_Status[$id]['i'] = $i;
		$farm_Status[$id]['j'] = $j;
		$farm_Status[$id]['k'] = $k;
		$farm_Status[$id]['l'] = $l;
		$farm_Status[$id]['m'] = $m;
		$farm_Status[$id]['n'] = $n;
		$farm_Status[$id]['o'] = $o;
		$farm_Status[$id]['p'] = $p;
		$farm_Status[$id]['q'] = $q;
		$farm_Status[$id]['r'] = $r;
	}
	$c_n += $n[$_QFG['uid']];
}

foreach($msg_temp as $key_log => $value) {
	if($key_log < 10000) {
		//被狗咬过~~
		if($key_log == 0) {
			//狗咬日志
			$sql1 = "SELECT `id`, `uid`, `fromid`, `count`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type = 4 and uid = " . $_REQUEST['ownerId'];
			$result = $_QFG['db']->query($sql1);
			$result = $_QFG['db']->fetch_array($result);
			if($result != null) {
				$sql = "UPDATE " . getTName("qqfarm_nclogs") . " set count = count+" . $value . ", time = " . $_QFG['timestamp'] . " where fromid = " . $_QFG['uid'] . " and type = 4 and uid = " . $_REQUEST['ownerId'];
			} else {
				$sql = "INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_REQUEST['ownerId'] . ", 4," . $value . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
			}
			$_QFG['db']->query($sql);
		}
		//未被狗咬^_^
		else {
			$cName = $cropstype[$key_log][cName];
			$cid = $key_log;
			foreach($farm_repertory as $key_1=>$value_1) {
				if($key_log == $value_1['cId']) {
					$flag = true;
					$farm_repertory[$key_1]['scroungeNumber'] = $farm_repertory[$key_1]['scroungeNumber'] + $c_n;
				}
			}
			if(!$flag) {
				$farm_repertory[] = array("cId"=>$key_log,"cName"=>$cName,"harvestNumber"=>0,"scroungeNumber"=>$c_n);
			}
			//偷取日志
			$sql1 = "SELECT `id`, `uid`, `cropid`, `fromid`, `count`,`counts`,`time`, `type` FROM  " .
				getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] .
				" and type=1 and uid = " . $_REQUEST['ownerId'] . " and time > " . ($_QFG['timestamp'] -
				3600);
			$query_r = $_QFG['db']->query($sql1);
			$value_r = $_QFG['db']->fetch_array($query_r);
			if ($value_r != null) {
				$result[] = $value_r;
				if (strpos($result[0][counts], ':') !== false) {
					$counts_ = explode(';', $result[0][counts]);
					$counts_chk = true;
					foreach ($counts_ as $key => $value_) {
						$counts_t = explode(':', $value_);
						if ($counts_t[0] == $cid) {
							$counts_t[1] += $n[$_QFG['uid']];
							$counts_chk = false;
							$counts_[$key] = join(':', $counts_t);
							break;
						}
					}
					if ($counts_chk) {
						$counts_all = $result[0][counts] . ";{$cid}:".$c_n."";
					} else {
						$counts_all = join(';', $counts_);
						//echo $counts_all;
					}
				} else {
					$counts_all = "{$cid}:".$c_n."";
				}
				$sql = "UPDATE " . getTName("qqfarm_nclogs") . " set count = count+1,counts='{$counts_all}',time = " . $_QFG['timestamp'] . " where id = " . $result[0][id];
			} else {
				$sql = "INSERT INTO " . getTName("qqfarm_nclogs") . " (`uid`, `type`, `count`,`counts`, `fromid`, `time`, `cropid`, `isread`) VALUES (" . $_REQUEST['ownerId'] . ", 1, 1,'{$cid}:".$c_n."', " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", " . $cid . ", 0);";
			}
			$_QFG['db']->query($sql);
		}
	}
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm_Status)) . "' where uid=" . intval($_REQUEST['ownerId']));
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit='" . qf_encode($farm_fruit) . "',repertory='" . qf_encode($farm_repertory) . "' where uid=" . $_QFG['uid']);

echo '[' . implode(',', $echo_str) . ']';

?>