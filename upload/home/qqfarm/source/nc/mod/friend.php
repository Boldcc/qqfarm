<?php

# 好友列表

if($_REQUEST['false'] == "refresh") {
	die('{"code":0}');
}

//好友条件
$condition = ' limit 0,1000';
if($_QSC['friendType'] == 1) {
	$friends = qf_getFriends(0);
	$friends .= (empty($friends) ? '' : ',') . $_QFG['uid'];
	$condition = " WHERE C.uid IN({$friends})" . $condition;
}

$query = $_QFG['db']->query("SELECT C.uid,C.username,C.money,C.vip,C.pf,N.exp FROM " . getTName("qqfarm_config") . " C Left JOIN " . getTName("qqfarm_nc") . " N ON N.uid=C.uid" . $condition);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

foreach($list as $key => $value) {
	$friendheadPic = qf_getheadPic($value['uid'], 'small');
	$exp = $value['exp'];
	$pf = $value['pf'];
	if($value['exp'] < 1) {
		$exp = 0;
		$pf = 0;
	}
	$vip = qf_decode($value['vip']);
	$friend_str[] = '{"userId":' . $value['uid'] . ',"uin":' . $value['uid'] . ',"userName":"' . $value['username'] . '","headPic":"' . $friendheadPic . '","yellowlevel":' .qf_toVipLevel($vip['exp']) . ',"yellowstatus":' .(int)$vip['status']. ',"exp":' . $exp . ',"money":' . $value['money'] . ',"pf":' . $pf . '}';
}
$friend_str = '[' . implode(',', $friend_str) . ']';

echo $friend_str;

?>