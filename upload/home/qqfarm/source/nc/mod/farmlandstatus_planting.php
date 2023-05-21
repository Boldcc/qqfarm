<?php

# 播种作物
$query = $_QFG['db']->query("SELECT package,exp,Status FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farmarr = qf_decode($list[0][Status]);
$packagearr = qf_decode($list[0][package]);
if($packagearr[$_REQUEST['cId']] == 0) {
	exit();
}
if($farmarr[$_REQUEST['place']]['a'] != 0) {
	exit();
}
$packagearr[$_REQUEST['cId']] = $packagearr[$_REQUEST['cId']] - 1;
$farmarr[$_REQUEST['place']]['a'] = (int)$_REQUEST['cId'];
$farmarr[$_REQUEST['place']]['b'] = 1;
$farmarr[$_REQUEST['place']]['c'] = 0;
$farmarr[$_REQUEST['place']]['d'] = 0;
$farmarr[$_REQUEST['place']]['e'] = 1;
$farmarr[$_REQUEST['place']]['f'] = 0;
$farmarr[$_REQUEST['place']]['g'] = 0;
$farmarr[$_REQUEST['place']]['h'] = 1;
$farmarr[$_REQUEST['place']]['i'] = 100;
$farmarr[$_REQUEST['place']]['j'] = 0;
$farmarr[$_REQUEST['place']]['k'] = 0;
$farmarr[$_REQUEST['place']]['l'] = 0;
$farmarr[$_REQUEST['place']]['m'] = 0;
$farmarr[$_REQUEST['place']]['n'] = array();
$farmarr[$_REQUEST['place']]['o'] = 0;
$farmarr[$_REQUEST['place']]['p'] = array();
$farmarr[$_REQUEST['place']]['q'] = $_QFG['timestamp'];
$farmarr[$_REQUEST['place']]['r'] = $_QFG['timestamp'];

foreach($packagearr as $key => $value) {
	if($value == 0) {
		unset($packagearr[$key]);
	}
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "',package='" . qf_encode($packagearr) . "' where uid=" . $_QFG['uid']);

//升级
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp=exp+2 where uid=" . $_QFG['uid']);
$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$levelup_arr = 'false';

if($exp_arr >= $levelup && $levelup < 93001) {
	include("source/nc/config/levelup.php"); //升级提示
}

echo '{"cId":' . $_REQUEST['cId'] . ',"farmlandIndex":' . $_REQUEST['place'] . ',"code":1,"poptype":0,"direction":"","exp":2,"levelUp":' . $levelup_arr . '}';

qf_addFeed('farmlandstatus_planting');

?>