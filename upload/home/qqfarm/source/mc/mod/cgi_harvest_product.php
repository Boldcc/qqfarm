<?php

# 动物收成
if($_REQUEST['harvesttype'] == "1") {
	$query = $_QFG['db']->query("SELECT Status,package,repertory FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$animal = qf_decode($list[0]['Status']);
	$mc_package = qf_decode($list[0][package]);
	$mc_repertory = qf_decode($list[0]['repertory']);
	$exp = $totalCome = 0;
	foreach($animal as $key => $value) {
		//更新成果
		if($value['cId'] == $_REQUEST['type'] && $value['totalCome'] > 0) {
			$flag = false;
			//已存在的只增加数量
			foreach($mc_repertory as $k => $v) {
				if($_REQUEST['type'] == $v['cId']) {
					$mc_repertory[$k]['harvest'] += $value['totalCome'];
					$flag = true;
				}
			}
			//不存在的创建对象
			if(!$flag) {
				$cName = $animalname[$_REQUEST['type']]['name'];
				$mc_repertory[] = array("cId" => $_REQUEST['type'], "cName" => $cName, "harvest" => $value['totalCome'], "scrounge" => 0);
			}
			++$exp;
			$totalCome += $value['totalCome'];
			$value['tou'] = "";
			$value['totalCome'] = 0;
			$animal[$key] = $value;//更新参数
		}
	}
	$totalCome == 0 && die('{"errorContent":"你来的也太晚了吧...","errorType":"1011"}');
	//更新背包
	$mc_package[$_REQUEST['type']] += $totalCome;
	//保存数据
	$result = $_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',exp=exp+" . intval($exp * $animalname[$_REQUEST['type']]['exp']) . ",package='" . qf_encode($mc_package) . "',repertory='" . qf_encode($mc_repertory) . "' where uid=" . $_QFG['uid']);
	//返回信息
	if($result) {
		die('{"addExp":' . $exp * $animalname[$_REQUEST['type']]['exp'] . ',"cId":' . $_REQUEST['type'] . ',"code":0,"harvestnum":' . $totalCome . ',"msg":"success","serial":-1}');
	}
}

if($_REQUEST['harvesttype'] == "2") {
	$serial = $_REQUEST['serial'];
	$query = $_QFG['db']->query("SELECT Status,package,feed,repertory FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$animal = qf_decode($list[0]['Status']);
	$feed = qf_decode($list[0]['feed']);
	$mc_package = qf_decode($list[0]['package']);
	$mc_repertory = qf_decode($list[0]['repertory']);
	//防加速齿轮
	if($animal[$serial]['growtime'] < $animaltime[$animal[$serial]['cId']][5]) {
		die('{"errorContent":"还没到收获时间呢，请不要着急","errorType":"1011"}');
	}

	//检查副产品
	$cid = "1" . $animal[$serial]['cId'];
	$cid1 = $animal[$serial]['cId'];
	if($cid1 < 1000) {
		die('{"errorContent":"此动物已被收获","errorType":"1011"}');
	}
	if($animal[$serial]['totalCome'] > 0) {
		die('{"errorContent":"请先收获副产品“' . $animalname[$cid1]['name'] . '”","errorType":"1011"}');
	}
	//入包
	$mc_package[$cid] += 1;
	//动物收成后
	$animal[$serial]['buyTime'] = 0;
	$animal[$serial]['cId'] = 0;
	$animal[$serial]['postTime'] = 0;
	$animal[$serial]['totalCome'] = 0;
	$animal[$serial]['tou'] = "";
	$animal[$serial]['growtime'] = 0;
	$animal[$serial]['p'] = 0;
	//更新成果
	$flag = false;
	//已存在的增加数量
	foreach((array)$mc_repertory as $k => $v) {
		if($cid == $v['cId']) {
			$mc_repertory[$k]['harvest'] += 1;
			$flag = true;
		}
	}
	//不存在则创建对象
	if(!$flag) {
		$cName = $animalname[$cid]['name'];
		$mc_repertory[] = array("cId" => $cid, "cName" => $cName, "harvest" => 1, "scrounge" => 0);
	}

	//保存数据
	$result = $_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set repertory='" . qf_encode($mc_repertory) . "',Status='" . qf_encode(array_values($animal)) . "',exp=exp+" . $animalname[$cid]['exp'] . ",package='" . qf_encode($mc_package) . "' where uid=" . $_QFG['uid']);
	//返回信息
	if($result) {
		die('{"addExp":' . $animalname[$cid]['exp'] . ',"cId":' . $cid1 . ',"code":0,"harvestnum":0,"msg":"success","serial":' . $serial . '}');
	}
}

?>