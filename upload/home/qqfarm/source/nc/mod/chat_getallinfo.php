<?php
# 用户状态

//type:1偷东西,2帮忙,3放虫，4狗咬，5放草，6出售 7消费 8Y币购买工具

include_once("source/nc/config/toolstype.php");

$log = array();
$uId = intval($_REQUEST['uId']);
$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_nclogs") . " WHERE uid = " . $uId . " and type not in(6,7,8,11,12) ORDER BY time DESC limit 0,50");
while($value = $_QFG['db']->fetch_array($query)) {
	$username = qf_getUserName($value[fromid]);
	if($value[type] == 1) {
		$counts_ = explode(';', $value[counts]);
		$counts_all = "";
		foreach($counts_ as $value_) {
			$counts_t = explode(':', $value_);
			$counts_all .= $counts_t[1] . "个" . $cropstype[$counts_t[0]][cName] . "\u3001";
		}
		if($counts_all != "") {
			$counts_all = substr($counts_all, 0,-6 );
		}
		$msg = "\"<font color=\\\"#009900\\\"><b>" . $username . "</b></font> 来农场偷窃，偷走{$counts_all}。\"";
	}
	elseif($value[type] == 2) {
		$counts_all = "";
		$counts_ = explode(':', $value[counts]);
		if($counts_[0] > 0) {
			$counts_all .= "除草" . $counts_[0] . "次\u3001";
		}
		if($counts_[1] > 0) {
			$counts_all .= "杀虫" . $counts_[1] . "次\u3001";
		}
		if($counts_[2] > 0) {
			$counts_all .= "浇水" . $counts_[2] . "次\u3001";
		}
		if($counts_all != "") {
			$counts_all = substr($counts_all, 0, -6);
		}
		$msg = "\"<font color=\\\"#009900\\\"><b>" . $username . "</b></font> 来农场帮忙{$counts_all}！\"";
	}
	elseif($value[type] == 3) {
		$msg = "\"<font color=\\\"#009900\\\"><b>" . $username . "</b></font> 来农场放虫，作物“生病”了！\"";
	}
	elseif($value[type] == 4) {
		$msg = "\"<font color=\\\"#009900\\\"><b>" . $username . "</b></font> 来农场偷东西被抓住，逃跑时遗落了" . $value[count] . "个金币。\"";
	}
	elseif($value[type] == 5) {
		$msg = "\"<font color=\\\"#009900\\\"><b>" . $username . "</b></font> 来农场放草，作物“生病”了！\"";
	}
	
	$log[] = "{\"domain\":2,\"msg\":" . $msg . ",\"time\":" . $value['time'] . "}";
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nclogs") . " set isread=1 where uid=" . $uId);
$log = '[' . implode(',', $log) . ']';

$money = $_QFG['db']->result($_QFG['db']->query('SELECT money FROM ' . getTName('qqfarm_config') . ' where uid=' . $uId), 0);
$query = $_QFG['db']->query('SELECT exp,repertory FROM ' . getTName('qqfarm_nc') . ' where uid=' . $uId);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$repertory = qf_decode($list[0][repertory]);
$user_str = '{"headPicBig":"' . qf_getheadPic($uId, 'big') . '","homePage":"' . qf_getHomePage($uId) . '","money":' . $money . ',"FBPrice":4,"uExp":' . $list[0]['exp'] . ',"uId":' . $uId . ',"uLevel":' . qf_toLevel($list[0]['exp']) . ',"uName":"' . qf_getUserName($uId) . '"}';

//标记已读
$_QFG['db']->query("UPDATE " . getTName("qqfarm_message") . " set isread=1 where toID=" . $uId);

$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_message") . " WHERE toID = " . $uId . " ORDER BY time DESC limit 0,50");
while($value = $_QFG['db']->fetch_array($query)) {
	if($value['fromID'] == $value['toID']) {
		$value["fromName"] = $value["toName"] = "主人";
	}
	if($chat) {
		$chat .= ',{"fromId":'.$value["fromID"].',"fromName":"'.$value["fromName"].'","toId":'.$uId.',"toName":"'.qf_getUserName($uId).'","time":'.$value["time"].',"msg":"'.$value["msg"].'","isReply":'.$value["isReply"].'}';
	} else {
		$chat = '{"fromId":'.$value["fromID"].',"fromName":"'.$value["fromName"].'","toId":'.$uId.',"toName":"'.qf_getUserName($uId).'","time":'.$value["time"].',"msg":"'.$value["msg"].'","isReply":'.$value["isReply"].'}';
	}
}

echo '{"user":' . $user_str . ',"log":' . $log . ',"chat":[' . $chat . '],"repertory":' . qf_getEchoCode($repertory) . '}';

?>