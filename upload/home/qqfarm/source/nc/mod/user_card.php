<?php

#收到的花信息

$query = $_QFG['db']->query("SELECT flower FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$flower = qf_decode($list[0]['flower']);

foreach($flower as $key => $value) {
	if($value['fromId'] == $_GET['uid'] && $value['time'] == $_GET['time']) {
		$echo_srt = '{"code":1,"time":' . $value['time'] . ',"uid":' . $value['fromId'] . ',"word":"' . $value['word'] . '"}';
	}
}

echo $echo_srt ? $echo_srt : '';

?>