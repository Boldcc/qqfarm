<?php

# 买动物

$cid = $_REQUEST['cId'];
$number = $_REQUEST['number'];

$query = $_QFG['db']->query("SELECT uid,Status,exp,decorative FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$animal = qf_decode($list[0]['Status']);
$decorative = qf_decode($list[0]['decorative']);

$money = $_QFG['db']->result($_QFG['db']->query("SELECT money FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);

$money_1 = $animaltype[$cid]['price'] * $number;
if($money < $money_1) {
	exit();//金币不足
}


$item2 = $decorative['item2'] + (3 > $decorative['item2'] ? 1 : 2);
$item3 = (0 == $decorative['item3']) ? 0 : ($decorative['item3'] + 2);
$animalnum = $item2 + $item3;

$item2count = 0;
$item3count = 0;
foreach($animal as $key => $value) {
	if(!$value['cId']|| $key >= $animalnum) {
		unset($animal[$key]);//删除非法数据
	}
	else {
		($value['cId'] > 1500) ? $item3count++ : $item2count++;
	}
}
$anicount = $item2count + $item3count;

if($number > $animalnum) {
	echo '{"errorContent":"你现在的等级只能再饲养' . ($animalnum - $anicount) . '只动物！","errorType":"1011"}';
	exit();
}
if($cid > 1500 && $number > ($item3 - $item3count)) {
	echo '{"errorContent":"你的棚只能再养' . ($item3 - $item3count) . '只动物！","errorType":"1011"}';
	exit();
}
if($cid > 1000 && $cid < 1500 && $number > ($item2 - $item2count)) {
	echo '{"errorContent":"你的窝只能再养' . ($item2 - $item2count) . '只动物！","errorType":"1011"}';
	exit();
}

for($n = 0; $n < $number; $n++) {
	$animal[] = array("buyTime"=>$_QFG['timestamp'],"cId"=>(int)$cid,"postTime"=>0,"totalCome"=>0,"tou"=>"", "growtime"=>0,"p"=>0);
	$buyanimal[] = array("buyTime"=>$_QFG['timestamp'], "cId"=>(int)$cid, "createTime"=>0,"growTime"=>0,"growTimeNext"=>($animaltime[$cid][0] - 1),"postTime"=>$_QFG['timestamp'],"productNum"=>0,"serial"=>$key ,"status"=>1,"statusNext"=>2,"totalCome"=>0);
}

//保存用户数据
$_QFG['db']->query(("UPDATE " . getTName("qqfarm_config") . " set money = money - " . $money_1) . " where uid=" . $_QFG['uid']);
$_QFG['db']->query(("UPDATE " . getTName("qqfarm_mc") . " set Status='" . qf_encode(array_values($animal)) . "',exp=exp+" . $number * 5) . " where uid=" . $_QFG['uid']);

//消费日志
$sql = "SELECT * FROM " . getTName("qqfarm_mclogs") . " WHERE uid = " . intval($_QFG['uid']) . " AND type = 10 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" . $_QFG['uid'];
$query = $_QFG['db']->query($sql);
while($value = $_QFG['db']->fetch_array($query)) {
	if (($value[type] == 10) && ($value[fromid] == $_QFG['supe_uid']) && ($number > 0)) {
		$list = $value[iid];
		$money = $value[money];
		$scount = $value[count];
		$stime = $value[time];
		$list = $list . "," . ($cid+10000);
		$scount = $scount . "," . $number;
		$money = $money + ($animaltype[$cid][price] * $number);
		$sql1 = "UPDATE " . getTName("qqfarm_mclogs") . " set iid = '" . $list .
		"', money = '" . $money . "', count ='" . $scount . "', time = " . $_QFG['timestamp'] .
		", isread = 1 where uid = " . intval($_QFG['uid']) .
		" AND type = 10 AND time > " . ($_QFG['timestamp'] - 3600) . " AND fromid =" .
		$_QFG['uid'];
	}
}
if(!$sql1 && $number > 0) {
	$sql1 = "INSERT INTO " . getTName("qqfarm_mclogs") . 
	" (`uid`, `type`, `count`, `fromid`, `time`, `iid`, `isread`, `money` ) VALUES (" .
	$_QFG['uid'] . ", 10," . $number . ", " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", " . 
	($cid+10000) . ", 1, " . $animaltype[$cid]['price'] * $number . ");";
}
$query = $_QFG['db']->query($sql1);

echo '{"addExp":' . ($number * 5) . ',"animal":' . qf_getEchoCode($buyanimal) . ',"code":0,"money":' . ($animaltype[$cid]['price'] * $number) . ',"msg":"success","num":' . $number . ',"uin":""}';

?>