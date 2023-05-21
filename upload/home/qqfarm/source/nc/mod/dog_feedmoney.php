<?php

# 狗粮提示

$query = $_QFG['db']->query("SELECT dog FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$dog = qf_decode($list[0]['dog']);
$hours = floor(($dog['dogFeedTime'] - $_QFG['timestamp']) / 3600);
if($hours < 0) {
	$hours = 0;
}

echo '{"hours":' . $hours . ',"saleOut":false}';

?>