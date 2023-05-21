<?php

# 升级房子-入库

$money = $_QFG['db']->result($_QFG['db']->query('SELECT money FROM ' . getTName('qqfarm_config') . ' where uid=' . $_QFG['uid']), 0);
$query = $_QFG['db']->query('SELECT exp,decorative FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$decorative = qf_decode($list[0]['decorative']);
$exp = $list[0]['exp'];

if($_REQUEST['type'] == '1') {
	$item = 'item2';
	$itemarr = array('1' => 0, '2' => 3000, '3' => 20000, '4' => 60000, '5' => 120000, '6' => 210000, '7' => 300000, '8' => 400000);
	$levelarr = array('1' => 0, '2' => 1, '3' => 4, '4' => 8, '5' => 12, '6' => 16, '7' => 20, '8' => 24);
} else {
	$item = 'item3';
	$itemarr = array('1' => 5000, '2' => 40000, '3' => 90000, '4' => 160000, '5' => 250000, '6' => 350000, '7' => 500000, '8' => 700000);
	$levelarr = array('1' => 2, '2' => 6, '3' => 10, '4' => 14, '5' => 18, '6' => 22, '7' => 26, '8' => 28);
}

$decorative[$item] = $decorative[$item] + 1;
if($money < $itemarr[$decorative[$item]] || $decorative[$item] > 8 || qf_toLevel($exp) < $levelarr[$decorative[$item]]) {
	die('{"errorContent":"请不要采用非法手段！","errorType":"1011"}');
}
$money = $money - $itemarr[$decorative[$item]];
$money1 = $itemarr[$decorative[$item]];

$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=' . $money . ' where uid=' . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set decorative='".qf_encode($decorative)."' where uid=" . $_QFG['uid']);

$decorative[item2] = '"2":{"id":102,"lv":' . $decorative[item2] . '},';
if($decorative[item3] == 0) {
	$decorative[item3] = '';
} else {
	$decorative[item3] = '"3":{"id":103,"lv":' . $decorative[item3] . '},';
}
echo '{"1":{"id":101,"lv":' . $decorative[item1] . '},' . $decorative[item2] . $decorative[item3] . '"4":{"id":104,"lv":' . $decorative[item4] . '},"code":1,"money":-' . $money1 . '}';

?>