<?php

# 加工厂

$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$query = $_QFG['db']->query("SELECT mc.package, nc.tools ,nc.fruit FROM " . getTName("qqfarm_mc") . " mc," . getTName("qqfarm_nc") . " nc where mc.uid=nc.uid and nc.uid=" . $_QFG['uid']); //便便
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$nc_tools = qf_decode($list[0]['tools']);
$nc_fruit = qf_decode($list[0]['fruit']);
$mc_package = qf_decode($list[0]['package']);


//化肥=5*便便+5*红玫瑰+1000*金币

$bbID = 1506;//[1506便便]
$hmgID = 41;//[41红玫瑰]
$hfID = 3;//[2高速化肥|3极速化肥]
if($mc_package[$bbID] < 5) {
	$msg = '<font color=\"#F47600\"><b>' . (5 - $mc_package[$bbID]) . '个牧场便便</b></font> ';
}
if($nc_fruit[$hmgID] < 5) {
	$msg .= '<font color=\"#F47600\"><b>' . (5 - $nc_fruit[$hmgID]) . '朵红玫瑰</b></font> ';
}
if($money < 1000) {
	$msg .= '<font color=\"#F47600\"><b>' . (1000 - $money) . '个金币</b></font>';
}
if($msg) {
	die('{"code":0,"direction":"您还差' . $msg . '。<br>魔法师没法帮您变出高速化肥哦！<br>集齐必要的东西再来试试吧！","poptype":0}');
}
$mc_package[$bbID] -= 5;
$nc_fruit[$hmgID] -= 5;
$nc_tools[$hfID] += 1;

$_QFG['db']->query("update " . getTName('qqfarm_config') . " set money=money-1000 where uid=" . $_QFG['uid']);
$_QFG['db']->query("update " . getTName('qqfarm_nc') . " set tools='" . qf_encode($nc_tools) . "', fruit='" . qf_encode($nc_fruit) . "' where uid=" . $_QFG['uid']);
$_QFG['db']->query("update " . getTName('qqfarm_mc') . " set package='" . qf_encode($mc_package) . "' where uid=" . $_QFG['uid']);

echo '{"code":1,"direction":"哇！好神奇！魔法师把您的<font color=\"#F47600\"><b>5个牧场便便</b></font>变成了<font color=\"#F47600\"><b>一袋极速化肥</b></font>！<br>同时您消耗了<font color=\"#F47600\"><b>5朵红玫瑰</b></font>和<font color=\"#F47600\"><b>1000个金币</b></font>。<br>魔法师已经把高速化肥悄悄的放进了您的农场包裹中，赶紧去看看<br>","money":-1000,"poptype":0}';

?>