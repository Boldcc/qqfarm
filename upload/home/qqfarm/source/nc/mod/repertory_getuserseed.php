<?php

# 用户背包

include_once("source/nc/config/toolstype.php");

$query = $_QFG['db']->query("SELECT package,tools FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$package = qf_decode($list[0]['package']);
foreach($package as $key => $value) {
	$hour = ($cropstype[$key]['growthCycle']) / 3600;
	if(0 < $value) {
		$packagearr[] = '{"type":1,"cId":' . $key . ',"cName":"' . $cropstype[$key]['cName'] . '","amount":' . $value . ',"lifecycle":' . $hour . ',"level":' . $cropstype[$key]['cLevel'] . '}';
	}
}

$tools = qf_decode($list[0]['tools']);
foreach($tools as $key => $value) {
	if(0 < $value && $key < 500) {
		$packagearr[] = '{"type":3,"tId":' . $key . ',"tName":"' . $Toolstype[30000 + $key]['tName'] . '","amount":' . $value . ',"depict":"' . $Toolstype[30000 + $key]['depict'] . '"}';
	}
}

echo '[' . implode(',', (array)$packagearr) . ']';

?>