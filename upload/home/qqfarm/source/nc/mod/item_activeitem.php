<?php

# 农场装饰

$activeItem = $_REQUEST['id'];

if($activeItem > 90000) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set activeItem=" . $activeItem . " where uid=" . $_QFG['uid']);
} else {
	$query = $_QFG['db']->query("SELECT dog,decorative FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$decorative = qf_decode($list[0]['decorative']);
	$dogstr = qf_decode($list[0]['dog']);
	if($activeItem > 80000) {
		if($_REQUEST['id'] == 80001) {
			$a = 3;
		} elseif($_REQUEST['id'] == 80003) {
			$a = 1;
		}
		$tid = $_REQUEST['id'] - 80000;
		$dogstr["dogs"][$tid]['status'] = 1;
		$dogstr["dogs"][$a]['status'] = 0;
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set dog='" . qf_encode($dogstr) . "' where uid=" . $_QFG['uid']);
	} else {
		foreach((array)$decorative as $item_type => $value) {
			foreach((array)$value as $key => $value1) {
				if($key == $activeItem) {
					$dec = $item_type;
					$decorative[$item_type][$activeItem]['status'] = 1;
				}
			}
		}
		if($dec) {
			foreach($decorative as $item_type => $value)
				if($item_type == $dec) {
					foreach((array)$value as $key => $value1) {
						if($key != $activeItem)
							$decorative[$item_type][$key]['status'] = 0;
					}
				}
		}
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set decorative='" . qf_encode($decorative) . "' where uid=" . $_QFG['uid']);
	}
}
echo '{"code":1,"id":' . $activeItem . '}';

?>