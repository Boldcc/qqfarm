<?php

# 金币购买装饰

$activeItem = $_REQUEST['itemId'];
$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$query = $_QFG['db']->query("SELECT decorative FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$decorative = qf_decode($list[0]['decorative']);
$buy_money = $itemtype[$activeItem]['price'];
$buy_time = $itemtype[$activeItem]['itemValidTime'];
$buy_type = $itemtype[$activeItem]['itemType'];
$buy_exp = $itemtype[$activeItem]['exp'];
if($money < $buy_money) {
	exit();
}

foreach((array)$decorative as $item_type => $value) {
	if($buy_type == $item_type) {
		foreach((array)$value as $key => $value1) {
			if($key == $activeItem) {
				die('{"direction":"你已经购买过了，不必重复购买！"}');
			} else {
				$dec = 1;
				$decorative[$item_type][$activeItem]['status'] = 1;
				$decorative[$item_type][$activeItem]['validtime'] = $_QFG['timestamp'] + $buy_time;
			}
		}
		if($dec) {
			foreach((array)$value as $key => $value1) {
				if($key != $activeItem) $decorative[$item_type][$key]['status'] = 0;
			}
		}
	}
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp = exp + " . $buy_exp . ",decorative='" . qf_encode($decorative) . "' where uid=" . $_QFG['uid']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money - " . $buy_money . " where uid=" . $_QFG['uid']);

//升级
$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$levelup_arr = 'false';
if($exp_arr >= $levelup && $levelup < 93001) {
	include("source/nc/config/levelup.php"); //升级提示
}

//消费日志
$sql = "INSERT INTO " . getTName("qqfarm_nclogs") .
		" (`uid`, `type`, `count`, `fromid`, `time`, `cropid`, `isread`, `money` ) VALUES (" . $_QFG['uid'] .
		", 11, 1, " . $_QFG['uid'] . ", " . $_QFG['timestamp'] .
		", '" . $_REQUEST['itemId'] . "', 1, '".$buy_money."');";
$_QFG['db']->query($sql);

echo '{"code":1,"exp":' . $buy_exp . ',"money":-' . $buy_money . ',"levelUp":' . $levelup_arr . '}';

?>