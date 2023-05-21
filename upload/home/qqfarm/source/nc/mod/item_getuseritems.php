<?php

# 初始化装饰

include_once("source/nc/config/toolstype.php");

$query = $_QFG['db']->query("SELECT dog,decorative FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$decorative = qf_decode($list[0]['decorative']);
foreach($decorative as $item_type => $value) {
	foreach($value as $key => $value1) {
		if($value1['validtime'] > $_QFG['timestamp'] or $value1['validtime'] == 1) {
			if($key == 1) {
				$get_itemName = "田园风光";
				$get_price = 0;
				$get_exp = 0;
			} elseif($key == 2) {
				$get_itemName = "茅草屋";
				$get_price = 0;
				$get_exp = 0;
			} elseif($key == 3) {
				$get_itemName = "木桩栅栏";
				$get_price = 0;
				$get_exp = 0;
			} elseif($key == 4) {
				$get_itemName = "茅草狗屋";
				$get_price = 0;
				$get_exp = 0;
			} else {
				$get_itemName = $itemtype[$key]['itemName'];
				$get_price = $itemtype[$key]['price'];
				$get_exp = $itemtype[$key]['exp'];
			}
			$decorative_str[] = '{"itemId":' . $key . ',"itemType":' . $item_type . ',"validTime":' . $value1['validtime'] . ',"status":' . $value1['status'] . ',"id":' . $key . ',"itemName":"' . $get_itemName . '","price":' . $get_price . ',"exp":' . $get_exp . '}';
		}
	}
}

$dog = qf_decode($list[0]['dog']);
foreach((array)$dog['dogs'] as $key => $value) {
	if($value['dogUnWorkTime'] > $_QFG['timestamp'] || $value['dogUnWorkTime'] == 1) {
		$get_itemName = $Toolstype[40000 + $key]['tName'];
		$get_price = $Toolstype[40000 + $key]['price'];
		$get_exp = 0;
		$decorative_str[] = '{"itemId":' . (80000 + $key) . ',"itemType":8,"validTime":' . $value['dogUnWorkTime'] . ',"status":' . $value['status'] . ',"id":' . (80000 + $key) . ',"itemName":"' . $get_itemName . '","price":' . $get_price . ',"exp":' . $get_exp . '}';
	}
}

echo '[' . implode(',', $decorative_str) . ']';

?>