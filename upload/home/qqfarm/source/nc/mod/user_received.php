<?php

# 收到花的信息

$query = $_QFG['db']->query("SELECT flower,nc_e FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$flower = qf_decode($list[0]['flower']);

foreach($flower as $key => $value) {
	$flowerId = $value['fId'];
	$flower[$key]['desc'] = $allFlower[$flowerId]['desc'];
	$flower[$key]['fName'] = $allFlower[$flowerId]['fName'];
}

if($list[0]['nc_e']) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set nc_e=0 where uid=" . $_QFG['uid']);
}

echo '{"code":1,"flowerPath":"module/nc/flower","myFlower":' . qf_getEchoCode($flower) . '}';

?>