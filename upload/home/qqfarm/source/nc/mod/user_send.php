<?php

# 送花

//to:好友ID, w:赠言,uIdx:主人ID,fId:花ID,farmTime:赠送时间

$fId = $_REQUEST['fId']; //所赠花的id

//扣减自己仓库中的鲜花~_~
$fruit = $_QFG['db']->result($_QFG['db']->query("SELECT fruit FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
$fruit = qf_decode($fruit);
$need_number = $allFlower[$fId]['need'][0]['amount']; //花数目
$need_flower = $allFlower[$fId]['need'][0]['cId']; //花类型
$fruit[$need_flower] -= $need_number;
if($allFlower[$fId]['need'][1]) {
	$need_number1 = $allFlower[$fId][need][1][amount]; //花数目
	$need_flower1 = $allFlower[$fId][need][1][cId]; //花类型
	$fruit[$need_flower1] -= $need_number1;
}
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set fruit = '" . qf_encode($fruit) . "' where uid=" . $_QFG['uid']);

//把花束放入对方花篮^_^
$flower = $_QFG['db']->result($_QFG['db']->query("SELECT flower FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['to'])), 0);
$flower = qf_decode($flower);
$flower[] = array("time"=>$_REQUEST['farmTime'],"fId"=>$fId,"fromId"=>$_QFG['uid'],"friendName"=>qf_getUserName($_REQUEST['uIdx']),"word"=>$_REQUEST['w']);
$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set flower = '" . qf_encode($flower) . "', nc_e=13 where uid=" . $_REQUEST['to']);

echo '{"code":1,"direction":"花束已经按照您的要求包装好，跟卡片一起寄出去了~ 我想收到的人一定会非常的开心哦！"}';

?>