<?php

# 牧场食物

$fruit = $_QFG['db']->result($_QFG['db']->query('SELECT fruit FROM ' . getTName('qqfarm_nc') . ' where uid=' . $_QFG['uid']), 0);
$fruit = qf_decode($fruit);

$id = 40;
$id1 = 3;
if($fruit[$id] == null) {
	$fruit[$id] = 0;
}
if($fruit[$id1] == null) {
	$fruit[$id1] = 0;
}

echo '[{"amount":' . $fruit[$id] . ',"tId":40,"tName":"牧草","type":4},{"amount":' . $fruit[$id1] . ',"tId":3,"tName":"胡萝卜","type":4}]';

?>