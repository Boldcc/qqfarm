<?php

# 拍蚊子和扫便便

if($_REQUEST['type'] == 1) {
	$query = $_QFG['db']->query("SELECT exp,bad FROM " . getTName("qqfarm_mc") . " where uid=" . intval($_REQUEST['uId']));
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	if($list[0][bad] == "") {
		exit();
	} else {
		$wenzi = explode(",", $list[0][bad]);
		$bad_num = count($wenzi);
		$pasture = 0;
		$number = $_REQUEST["num"];
		if($number > $bad_num) {
			$number = $bad_num;
		}
		for($i = 0; $i < $bad_num; $i++) {
			if($wenzi[$i] != $_QFG['uid'] && $pasture != $number) {
				unset($wenzi[$i]);
				$pasture = $pasture + 1;
			} else {
				$bad_all = $bad_all . $wenzi[$i];
				if($i < $bad_num - 1) {
					$bad_all = $bad_all . ",";
				}
			}
		}
		$number = $pasture;
	}
	$int = strlen($bad_all);
	if($int == 0) {
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set badtime=0 where uid=" . intval($_REQUEST['uId']));
	} else {
		$str = substr($bad_all, $int - 1, 1);
		if($str == ",") {
			$bad_all = substr($bad_all, 0, $int - 1);
		}
	}
	$exp = 3 * $number;
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set bad='" . $bad_all . "' where uid=" . intval($_REQUEST['uId']));
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set exp = exp + " . intval($exp) . " where uid=" . $_QFG['uid']);
	//拍蚊子日志
	if($_QFG['uid'] != $_REQUEST['uId']) {
		$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . intval($_REQUEST['uId']) . " AND type = 7 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
		$query = $_QFG['db']->query($sql);
		while($value = $_QFG['db']->fetch_array($query)) {
			if(($value[type] == 7) && ($value[fromid] == $_QFG['uid'])) {
				$scount = $value[count];
				$stime = $value[time];
				$scount = $scount + 1;
				$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . intval($_REQUEST['uId']) . " AND type = 7 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
			}
		}
		if((!$sql1)) {
			$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $_REQUEST['uId'] . ", 7,1, " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0, 0);";
		}
		if($sql1) $query = $_QFG['db']->query($sql1);
	}
	//输出信息
	echo '{"addExp":' . $exp . ',"cId":1,"num":' . $number . ',"pos":' . $_REQUEST['pos'] . '}';
}

if($_REQUEST['type'] == 2) {
	$cId = "1506";
	$bb = $_QFG['db']->result($_QFG['db']->query("SELECT dabian FROM " . getTName("qqfarm_mc") . " where uid=" . intval($_REQUEST['uId'])), 0);
	$mc_package = $_QFG['db']->result($_QFG['db']->query("SELECT package FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']), 0);
	$mc_package = qf_decode($mc_package);
	if($bb <= 0) {
		echo '{"errorContent":"您下手太慢，便便已经被清理了","errorType":"1004"}';
		exit();
	}
	//便便成果
	$mc_repertory = $_QFG['db']->result($_QFG['db']->query("SELECT repertory FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']), 0);
	$mc_repertory = qf_decode($mc_repertory);
	$flag = false;
	foreach((array)$mc_repertory as $key => $val) {
		if(1506 == $val['cId']) {
			$mc_repertory[$key]['harvest'] += 1;
			$flag = true;
		}
	}
	if(!$flag) {
		$mc_repertory[] = array("cId" => 1506, "cName" => "便便", "harvest" => 1, "scrounge" => 0);
	}
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set repertory='" . qf_encode($mc_repertory) . "' where uid=" . $_QFG['uid']);
	//便便成果
	$bb = $bb - 1;
	$mc_package[$cId] += 1;
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set dabian=" . $bb . "  where uid=" . intval($_REQUEST['uId']));
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set package='" . qf_encode($mc_package) . "' where uid=" . $_QFG['uid']);
	//帮忙扫便日志
	if($_QFG['uid'] != $_REQUEST['uId']) {
		$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . intval($_REQUEST['uId']) . " AND type = 8 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
		$query = $_QFG['db']->query($sql);
		while($value = $_QFG['db']->fetch_array($query)) {
			if(($value[type] == 8) && ($value[fromid] == $_QFG['uid'])) {
				$scount = $value[count];
				$stime = $value[time];
				$scount = $scount + 1;
				$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . intval($_REQUEST['uId']) . " AND type = 8 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
			}
		}
		if((!$sql1)) {
			$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . "(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(" . $_REQUEST['uId'] . ", 8,1, " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0, 0);";
		}
		if($sql1) $query = $_QFG['db']->query($sql1);
	}
	echo '{"num":1,"pos":' . $_REQUEST['pos'] . ',"repNum":1,"type":2}';
}

?>