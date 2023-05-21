<?php

# 农场除草

$pieces = explode(",", $_REQUEST['place']);
$query = $_QFG['db']->query("SELECT Status,tips,exp,weed,dog FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$farm = qf_decode($list[0]['Status']);
$farmweed = qf_decode($list[0]['weed']);

if(intval($_REQUEST['ownerId']) != $_QFG['uid']) {
	$Tips = qf_decode($list[0]['tips']);
}

$ZONG = $_QFG['db']->result($_QFG['db']->query("SELECT zong FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);

$echo_str = array();
foreach($pieces as $id) {
	$f = $farm[$id]['f'];
	$p = (array)$farm[$id]['p'];
	if($f > 0) {
		$cnt_all = array_count_values((array)$farmweed[$id]);
		$cnt_you = $cnt_all[$_QFG['uid']]; //得到你在此地种草的数目
		if($f <= $cnt_you) {
			die('{"code":0,"direction":"证据是不能毁灭的！","farmlandIndex":' . $id . ',"poptype":1,"weed":' . $f . '}');
		} else {
			unset($farmweed[$id][$f]);
			if($f == 1) {
				unset($farmweed[$id]);
			}
		}
		$f -= 1;
		//限制除草
		if($ZONG > 150) {
			$echo_str[] = '{"code":1,"direction":"' . $Tips['weed_b'] . '","exp":0,"farmlandIndex":' . $id . ',"levelUp":false,"money":2,"poptype":1,"weed":' . $f . '}';
		} else {
			$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp = exp + 2,zong=zong+1 where uid=" . $_QFG['uid']);
			$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
			$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
			$levelup_arr = 'false';
			if($exp_arr >= $levelup && $levelup < 93001) {
				include("source/nc/config/levelup.php"); //升级提示
			}
			$echo_str[] = '{"code":1,"direction":"' . $Tips['weed_b'] . '","exp":2,"farmlandIndex":' . $id . ',"levelUp":' . $levelup_arr . ',"money":2,"poptype":1,"weed":' . $f . '}';
		}
	}
	foreach($p as $pk => $pv) {
		if($pv == 2) {
			unset($p[$pk]);
			break;
		}
	}
	$farm[$id]['p'] = $p;
	$farm[$id]['f'] = $f;
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money = money + 2 where uid=" . $_QFG['uid']);

if(intval($_REQUEST['ownerId']) == $_QFG['uid']) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm)) . "',weed='" . qf_encode($farmweed) . "' where uid=" . $_QFG['uid']);
	qf_addFeed('farmlandstaus_clearweed1');
} else {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm)) . "', weed='" . qf_encode($farmweed) . "' where uid=" . $_REQUEST['ownerId']);
	qf_addFeed('farmlandstaus_clearweed2');
	//帮忙除草日志
	$sql1 = "SELECT `id`, `uid`, `cropid`, `fromid`, `count`,`counts`,`time`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type=2 and uid = " . $_REQUEST['ownerId'] . " and time > " . ($_QFG['timestamp'] - 3600);
	$query_r = $_QFG['db']->query($sql1);
	$value_r = $_QFG['db']->fetch_array($query_r);
	if($value_r != null) {
		$result[] = $value_r;
		if(strpos($result[0][counts], ':') !== false) {
			$counts_ = explode(':', $result[0][counts]);
			$counts_[0]++;
			$counts_all = join(':', $counts_);
		} else {
			$counts_all = "1:0:0";
		  }
		  $sql = "UPDATE " . getTName("qqfarm_nclogs") . " set count = count+1,counts='{$counts_all}',time = " . $_QFG['timestamp'] . " where id = " . $result[0][id];
     } else {
        $sql = "INSERT INTO " . getTName("qqfarm_nclogs") ." (`uid`, `type`, `count`,`counts`, `fromid`, `time`, `cropid`, `isread` ) VALUES (" . $_REQUEST['ownerId'] . ", 2, 1,'1:0:0', " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
    }
    $_QFG['db']->query($sql);
}

echo '' . implode(',', $echo_str) . '';

?>