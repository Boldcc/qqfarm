<?php

# 用户仓库

$fruit = $_QFG['db']->result($_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$fruit = qf_decode($fruit);

$fruitarr = array();
foreach($fruit as $key => $value) {
	if(0 < $value) {
		$fruitarr[] = '{"cId":' . $key . ',"cName":"' . $cropstype[$key]['cName'] . '","level":"' . $cropstype[$key]['cLevel'] . '","amount":' . $value . ',"price":"' . $cropstype[$key]['sale'] . '"}';
	}
}

if($fruitarr) {
	$fruitarr = '[' . implode(',', $fruitarr) . ']';
} else {
	$fruitarr = '[]';
}

echo '{"allFlower":' . qf_getEchoCode(array_values($allFlower)) . ',"crop":' . $fruitarr . ',"flowerPath":"module/nc/flower"}';

?>