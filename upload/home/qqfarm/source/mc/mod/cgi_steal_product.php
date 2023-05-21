<?php

# 偷动物

$query = $_QFG['db']->query("SELECT Status FROM " . getTName("qqfarm_mc") . " where uid=" . intval($_REQUEST['uId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$animal = qf_decode($list[0]['Status']);

$query_2 = $_QFG['db']->query("SELECT repertory,package FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query_2)) {
	$list_2[] = $value;
}
$mc_package = qf_decode($list_2[0]['package']);
$mc_repertory = qf_decode($list_2[0]['repertory']);

//开始偷取
$tounum = 0;
foreach($animal as $key => $value) {
	$touID = explode(',',$value['tou']);
	if($_REQUEST['type'] == $value['cId'] && !in_array($_QFG['uid'], $touID)) {
		if($animaltype[$_REQUEST['type']]['output'] / 2 < $value[totalCome]) {
			$flag = false;
			//已存在的增加数量
			foreach($mc_repertory as $k => $v) {
				if($_REQUEST['type'] == $v['cId']) {
					$mc_repertory[$k]['scrounge']++;
					$flag = true;
				}
			}
			//不存在则创建对象
			if(!$flag) {
				$cName = $animalname[$_REQUEST['type']]['name'];
				$mc_repertory[] = array("cId" => $_REQUEST['type'], "cName" => $cName, "harvest" => 0, "scrounge" => 1);
			}
			++$tounum;
			$value['totalCome']--;
			if($value['tou']) {
				$value['tou'] .= ','.$_QFG['uid'] ;
			} else {
				$value['tou'] = $_QFG['uid'] ;
			}
			
			$animal[$key] = $value;//更新参数
		}
	}
}
$tounum == 0 && die('{"errorContent":"你来的也太晚了吧...","errorType":"1011"}');

//偷完入库
$mc_package[$_REQUEST['type']] = $mc_package[$_REQUEST['type']] + $tounum;//用户背包
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set repertory='" . qf_encode($mc_repertory) . "', package='" . qf_encode($mc_package) . "' where uid=" . $_QFG['uid']);


//更新主人动物状态
$_QFG['db']->query("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "' where uid=" . intval($_REQUEST['uId']));


//更新偷取日志
$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid=" . intval($_REQUEST['uId']) . " AND type=1 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	if(($value[type] == 1) && ($value[fromid] == $_QFG['uid']) && ($tounum > 0)) {
		$list = $value[iid];
		$scount = $value[count];
		$stime = $value[time];
		$list = $list . "," . $_REQUEST['type'];
		$scount = $scount . "," . $tounum;
		$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set iid = '" . $list . "', count ='" . $scount . "', time = " . $_QFG['timestamp'] . ", isread = 0 where uid = " . intval($_REQUEST['uId']) . " AND type = 1 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
	}
}
if((!$sql1) && ($tounum > 0)) {
	$sql1 = 'INSERT INTO ' . getTName('qqfarm_mclogs') . '(`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money`) VALUES(' . $_REQUEST['uId'] . ', 1,' . $tounum . ', ' . $_QFG['uid'] . ', ' . $_QFG['timestamp'] . ', ' . $_REQUEST['type'] . ', 0, 0);';
}
if($sql1) $query = $_QFG['db']->query($sql1);

//返回信息
echo '{"cId":' . $_REQUEST['type'] . ',"harvestnum":' . $tounum . '}';

?>