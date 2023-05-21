<?php

#消费,卖出

$uId = intval($_REQUEST['uIdx']);
$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . $uId . " and type in(4,9,10) ORDER BY time DESC limit 0,50");

while($value = $_QFG['db']->fetch_array($query)) {
	if($value[type] == 10) {
		$scrids = array();
		$scrids = explode(",", $value[iid]);
		$scrcots = array();
		$scrcots = explode(",", $value[count]);
		$scrougestr = "";
		for ($i = 0; $i < count($scrids); $i++) {
			$scrougestr = $scrougestr . $scrcots[$i] . $animalname[$scrids[$i]][liangci] . $animalname[$scrids[$i]][name];
			if ($i + 1 < count($scrids)) {
				$scrougestr = $scrougestr . "，";
			} else {
				$scrougestr = $scrougestr . "，";
			}
		}
		$msg = '"从商店购买了' . $scrougestr . '共付出' . $value[money] . '金币。"';
	} elseif($value[type] == 4) {
		$msg = '"共花了' . $value[money] . '金币购买' . $value[count] . '棵草料放入饲料机内。"';
	} elseif($value[type] == 9) {
		$scrids = array();
		$scrids = explode(',', $value[iid]);
		$scrcots = array();
		$scrcots = explode(',', $value[count]);
		$scrougestr = '';
		for($i = 0; $i < count($scrids); $i++) {
			$scrougestr = $scrougestr . $scrcots[$i] . $animalname[$scrids[$i]][liangci] . $animalname[$scrids[$i]][name];
			if($i + 1 < count($scrids)) {
				$scrougestr = $scrougestr . '，';
			} else {
				$scrougestr = $scrougestr . '，';
			}
		}
		$msg = '"卖出了仓库里的' . $scrougestr . '共收入' . $value[money] . '金币。"';
	}
	if($cost) {
		$cost .= ',{"msg":' . $msg . ',"time":' . $value['time'] . '}';
	} else {
		$cost = '{"msg":' . $msg . ',"time":' . $value['time'] . '}';
	}
}

echo '{"code":1,"cost":['.$cost.']}';

?>