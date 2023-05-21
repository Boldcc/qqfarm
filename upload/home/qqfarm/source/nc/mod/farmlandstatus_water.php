<?php

# 作物浇水

$farm = $_QFG['db']->result($_QFG['db']->query("SELECT Status FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId'])), 0);
$farm = qf_decode($farm);
$farm_arr = $farm[$_REQUEST['place']]['p'];
foreach($farm_arr as $key_pw => $value_pw) {
	if($value_pw == 3) {
		unset($farm_arr[$key_pw]);
		break;
	}
}
$farm[$_REQUEST['place']]['p'] = $farm_arr;
if($farm[$_REQUEST['place']]['h'] == 1) {
	exit();
}
$farm[$_REQUEST['place']]['h'] = 1;

if(intval($_REQUEST['ownerId']) == $_QFG['uid']) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money+2 where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp=exp+2,Status='" . qf_encode(array_values($farm)) . "' where uid=" . $_QFG['uid']);
	//升级
	$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup_arr = 'false';
	if($exp_arr >= $levelup && $levelup < 93001) {
		include("source/nc/config/levelup.php"); //升级提示
	}
	qf_addFeed('farmlandstatus_water1');
} else {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money+2 where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp=exp+2 where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm)) . "' where uid=" . $_REQUEST['ownerId']);
	//升级
	$query = $_QFG['db']->query("SELECT tips,levelup,exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$Tips = qf_decode($list[0]['tips']);
	$exp_arr = $list[0]['exp'];
	$levelup = $list[0]['levelup'];
	$levelup_arr = 'false';
	if($exp_arr >= $levelup && $levelup < 93001) {
		include("source/nc/config/levelup.php"); //升级提示
	}
	//帮忙浇水日志
	$sql1 = "SELECT `id`, `uid`, `cropid`, `fromid`, `count`,`counts`,`time`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type=2 and uid = " . $_REQUEST['ownerId'] . " and time > " . ($_QFG['timestamp'] - 3600);
	$query_r = $_QFG['db']->query($sql1);
	$value_r = $_QFG['db']->fetch_array($query_r);
	if($value_r != null) {
		$result[] = $value_r;
                if (strpos($result[0][counts], ':') !== false) {
                    $counts_ = explode(':', $result[0][counts]);
                    $counts_[2]++;
                    $counts_all = join(':', $counts_);
                } else {
                    $counts_all = "0:0:1";
                }
		       $sql = "UPDATE " . getTName("qqfarm_nclogs") . " set count = count+1,counts='{$counts_all}',time = " . $_QFG['timestamp'] . " where id = " . $result[0][id];
     } else {
        $sql = "INSERT INTO " . getTName("qqfarm_nclogs") ." (`uid`, `type`, `count`,`counts`, `fromid`, `time`, `cropid`, `isread` ) VALUES (" . $_REQUEST['ownerId'] . ", 2, 1,'0:0:1', " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
    }
    $_QFG['db']->query($sql);
	qf_addFeed('farmlandstatus_water2');
}

echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"code":1,"poptype":1,"direction":"' . $Tips['water_b'] . '","money":2,"exp":2,"levelUp":' . $levelup_arr . ',"humidity":' . $farm[$_REQUEST['place']]['h'] . '}';

?>