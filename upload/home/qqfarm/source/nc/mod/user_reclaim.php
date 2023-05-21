<?php

# 开垦土地

$tudiarr = array(
	"6" => array("level" => 5, "money" => 10000),
	"7" => array("level" => 7, "money" => 20000),
	"8" => array("level" => 9, "money" => 30000),
	"9" => array("level" => 11, "money" => 50000),
	"10" => array("level" => 13, "money" => 70000),
	"11" => array("level" => 15, "money" => 90000),
	"12" => array("level" => 17, "money" => 120000),
	"13" => array("level" => 19, "money" => 150000),
	"14" => array("level" => 21, "money" => 180000),
	"15" => array("level" => 23, "money" => 230000),
	"16" => array("level" => 25, "money" => 300000),
	"17" => array("level" => 27, "money" => 500000)
);

$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$query = $_QFG['db']->query("SELECT Status,reclaim,exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$reclaim = $list[0]['reclaim'];
if($money < $tudiarr[$reclaim]['money'] || $list[0]['exp'] < $tudiarr[$reclaim]['exp']) {
	exit();
}

$Status = qf_decode($list[0]['Status']);
foreach($Status as $key => $value) {
	if($key >= $reclaim) {
		unset($Status[$key]);
	}
}
$Status[$reclaim] = array("a"=>0,"b"=>0,"c"=>0,"d"=>0,"e"=>1,"f"=>0,"g"=>0,"h"=>1,"i"=>100,"j"=>0,"k"=>0,"l"=>0,"m"=>0,"n"=>array(),"o"=>0,"p"=>array(),"q"=>0,"r"=>1251351725);
$Status = array_values($Status);

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money = money - " . $tudiarr[$reclaim]['money'] . " where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode($Status) . "',reclaim = reclaim + 1 where uid=" . $_QFG['uid']);

echo '{"code":1,"direction":"","money":-' . $tudiarr[$reclaim]['money'] . '}';

?>