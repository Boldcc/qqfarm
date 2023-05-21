<?php

# 牧场操作日志

//1偷；2帮产；3背包加草；4自己买草；5帮好友买草；6放蚊；7拍蚊；8清理便便；9出售；10购买。

$uId = intval($_REQUEST['uId']);

//金币
$money = $_QFG['db']->result($_QFG['db']->query('SELECT money FROM ' . getTName('qqfarm_config') . ' where uid=' . $uId), 0);

//牧场经验值
$exp = $_QFG['db']->result($_QFG['db']->query('SELECT exp FROM ' . getTName('qqfarm_mc') . ' where uid=' . $uId), 0);

$mclog = array();
$query = $_QFG['db']->query('SELECT * FROM ' . getTName('qqfarm_mclogs') . ' WHERE uid=' . $uId . ' and type not in(4,9,10) ORDER BY time DESC limit 0,50');
while($value = $_QFG['db']->fetch_array($query)) {
	$username = qf_getUserName($value[fromid]);
	if($value[type] == 1) {
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
				$scrougestr = $scrougestr . '。';
			}
		}
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场偷走了' . $scrougestr . '"';
	} elseif($value[type] == 2) {
		$helpids = array();
		$helpids = explode(',', $value[iid]);
		$helpcots = array();
		$helpcots = explode(',', $value[count]);
		$helpstr = '';
		for($i = 0; $i < count($helpids); $i++) {
			$helpstr = $helpstr . $helpcots[$i] . $animalname[10000 + $helpids[$i]][liangci] . $animalname[10000 + $helpids[$i]][name] . '赶去' . $animalname[10000 + $helpids[$i]][act];
			if($i + 1 < count($helpids)) {
				$helpstr = $helpstr . '，';
			} else {
				$helpstr = $helpstr . '。';
			}
		}
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 帮忙把' . $helpstr . '"';
	} elseif($value[type] == 3) {
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场从自己包裹里的' . $value[count] . '棵草料添加到饲料机内。"';
	} elseif($value[type] == 5) {
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场帮忙共花了' . $value[money] . '金币购买' . $value[count] . '棵草料放入饲料机内"';
	} elseif($value[type] == 6) {
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场放了' . $value[count] . '只蚊子，真不是好人！"';
	} elseif($value[type] == 7) {
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场帮忙拍了' . $value[count] . '只蚊子！"';
	} elseif($value[type] == 8) {
		$msg = '"<font color=\"#009900\"><b>' . $username . '</b></font> 来牧场帮忙清扫了' . $value[count] . '个便便！"';
	}
	$mclog[] = '{"domain":2,"msg":' . $msg . ',"time":' . $value['time'] . '}';
}
$mclog = '[' . implode(',', $mclog) . ']';

//牧场成果
$repertory = $_QFG['db']->result($_QFG['db']->query('SELECT repertory FROM ' . getTName('qqfarm_mc') . ' where uid=' . $uId), 0);
$repertory = qf_getEchoCode(qf_decode($repertory));

//标记已读
$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mclogs') . ' set isread=1 where uid=' . $_QFG['uid']);

//输出信息
echo '{"log":' . $mclog . ',"repertory":' . $repertory . ',"user":{"homePage":"' . qf_getHomePage($uId) . '","money":' . $money . ',"uExp":' . $exp . ',"uId":' . $uId . ',"uLevel":' . qf_toLevel($exp) . ',"uName":"' . qf_getUserName($uId) . '"}}';

?>