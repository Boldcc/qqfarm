<?php

# 起地作物

$query = $_QFG['db']->query("SELECT Status,exp,package FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farm_arr = qf_decode($list[0][Status]);

if(0 < $farm_arr[$_REQUEST['place']]['a']) {
	if(7 <= $farm_arr[$_REQUEST['place']]['b']) {
		$jj = rand(1, 10);
		$scarifyexp = 3;
	} else {
		$scarifyexp = 0;
		$jj = 2;
	}
	$farm_arr[$_REQUEST['place']]['a'] = 0;
	$farm_arr[$_REQUEST['place']]['b'] = 0;
	$farm_arr[$_REQUEST['place']]['c'] = 0;
	$farm_arr[$_REQUEST['place']]['d'] = 0;
	$farm_arr[$_REQUEST['place']]['e'] = 1;
	$farm_arr[$_REQUEST['place']]['f'] = 0;
	$farm_arr[$_REQUEST['place']]['g'] = 0;
	$farm_arr[$_REQUEST['place']]['h'] = 1;
	$farm_arr[$_REQUEST['place']]['i'] = 100;
	$farm_arr[$_REQUEST['place']]['j'] = 0;
	$farm_arr[$_REQUEST['place']]['k'] = 0;
	$farm_arr[$_REQUEST['place']]['l'] = 0;
	$farm_arr[$_REQUEST['place']]['m'] = 0;
	$farm_arr[$_REQUEST['place']]['n'] = array();
	$farm_arr[$_REQUEST['place']]['o'] = 0;
	$farm_arr[$_REQUEST['place']]['p'] = array();
	$farm_arr[$_REQUEST['place']]['q'] = 0;
	$farm_arr[$_REQUEST['place']]['r'] = 0;
	//升级
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp=exp+$scarifyexp where uid=" . $_QFG['uid']);
	$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
	$levelup_arr = 'false';
	if($exp_arr >= $levelup && $levelup < 93001) {
		include("source/nc/config/levelup.php"); //升级提示
	}
	if($jj == 1) {
		qf_getCache('hide');
		$package = qf_decode($list[0][package]);
		srand((float)microtime() * 10000000);
		$hideSeed = count($_HIDE['seed']) > 1 ? $_HIDE['seed'] : array(25,56,46,62,67,69,70,75,80,81,82,92,93,94,101,102,103,104,105,106,107,108,109,111,112,114,113,118,125);
		$zhongzi = array_rand($hideSeed);
		$zhongzi = $hideSeed[$zhongzi];
		$num = rand(1, 2);
		$package[$zhongzi] = $package[$zhongzi] + $num;
		$cName = $cropstype[$zhongzi][cName];
		$maturingTime = $cropstype[$zhongzi][maturingTime];
		$output = $cropstype[$zhongzi][output];
		$exp = $cropstype[$zhongzi][cropExp];
		$sale = $cropstype[$zhongzi][sale];
		$growTime = $cropstype[$zhongzi][growthCycle];
		$up = $cropstype[$zhongzi][cLevel];
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set package='" . qf_encode($package) . "' where uid=" . $_QFG['uid']);
		echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"code":1,"direction":"","exp":' . $scarifyexp . ',"levelUp":' . $levelup_arr . ',"randsend":{"desc":"' . $cName . '","id":"' . $zhongzi . '","name":"' . $cName . '","harvestTimes":"' . $maturingTime . '","output":"' . $output . '","exp":"' . $exp . '","sale":"' . $sale . '","growTime":"' . $growTime . '","num":' . $num . ',"level":"' . $up . '","type":1}}';
	} else {
		echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"code":1,"direction":"","exp":' . $scarifyexp . ',"levelUp":' . $levelup_arr . '}';
	}
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farm_arr)) . "' where uid=" . $_QFG['uid']);
} else {
	echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"code":0,"poptype":1,"direction":"已经锄过这块地了哟！"}';
}

qf_addFeed('farmlandstatus_scarify');

?>