<?php

# 作物输出

if($_REQUEST['ownerId'] == 0) {
	$uid = $_QFG['uid'];
} else {
	$uid = $_REQUEST['ownerId'];
}

$query = $_QFG['db']->query("SELECT Status FROM " . getTName("qqfarm_nc") . " where uid=" . $uid);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farmarr = qf_decode($list[0][Status]);
$a = $farmarr[$_REQUEST['place']]['a'] ;
$b = $farmarr[$_REQUEST['place']]['b'] ;
$c = $farmarr[$_REQUEST['place']]['c'] ;
$d = $farmarr[$_REQUEST['place']]['d'] ;
$e = $farmarr[$_REQUEST['place']]['e'] ;
$f = $farmarr[$_REQUEST['place']]['f'] ;
$g = $farmarr[$_REQUEST['place']]['g'] ;
$h = $farmarr[$_REQUEST['place']]['h'] ;
$i = $farmarr[$_REQUEST['place']]['i'] ;
$j = $farmarr[$_REQUEST['place']]['j'] ;
$k = $farmarr[$_REQUEST['place']]['k'] ;
$l = $farmarr[$_REQUEST['place']]['l'] ;
$m = $farmarr[$_REQUEST['place']]['m'] ;
$n = $farmarr[$_REQUEST['place']]['n'] ;
$o = $farmarr[$_REQUEST['place']]['o'] ;
$p = $farmarr[$_REQUEST['place']]['p'] ;
$q = $farmarr[$_REQUEST['place']]['q'] ;
$r = $farmarr[$_REQUEST['place']]['r'] ;
$p = (array)$farmarr[$_REQUEST['place']]['p'] ;
$zuowutime = $_QFG['timestamp'] - $q;
if($zuowutime < $cropstype[$a][growthCycle]) {
	exit();
}
$b = 6;
$c = 0;
$d = 0;
$e = 1;
$f = 0;
$g = 0;
$h = 1;
$j = $farmarr[$_REQUEST['place']]['j'];
$k = $cropstype[$a][output];
foreach($p as $pk => $pv) {
	if($pv == 1 or $pv == 2) {
		$cnt += ceil(($_QFG['timestamp'] - $pk) / 300) + 1;
	} else
		if($pv == 3) {
			$cnt += ceil(($_QFG['timestamp'] - $pk) / 300) * 2 + 2;
		}
}
if($cnt > 50) {
	$cnt = 50;
}
$k = ceil($k * (100 - $cnt) / 100);
$l = floor($k * 0.6);
$m = $k;
$farmarr[$_REQUEST['place']]['b'] = $b;
$farmarr[$_REQUEST['place']]['c'] = $c;
$farmarr[$_REQUEST['place']]['d'] = $d;
$farmarr[$_REQUEST['place']]['e'] = $e;
$farmarr[$_REQUEST['place']]['f'] = $f;
$farmarr[$_REQUEST['place']]['g'] = $g;
$farmarr[$_REQUEST['place']]['h'] = $h;
$farmarr[$_REQUEST['place']]['j'] = $j;
$farmarr[$_REQUEST['place']]['k'] = $k;
$farmarr[$_REQUEST['place']]['l'] = $l;
$farmarr[$_REQUEST['place']]['m'] = $m;

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "' where uid=" . $uid);

echo '{"farmlandIndex":' . $_REQUEST['place'] . ',"status":{"action":' . qf_getEchoCode($p) . ',"cId":' . $a . ',"cropStatus":' . $b . ',"fertilize":' . $o . ',"harvestTimes":' . $j . ',"health":' . $i . ',"humidity":' . $h . ',"leavings":' . $m . ',"min":' . $l . ',"oldhumidity":' . $e . ',"oldpest":' . $d . ',"oldweed":' . $c . ',"output":' . $k . ',"pest":' . $g . ',"plantTime":' . qf_getEchoCode($q) . ',"thief":' . qf_getEchoCode($n) . ',"updateTime":' . qf_getEchoCode($r) . ',"weed":' . $f . '}}';

?>