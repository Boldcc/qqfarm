<?php

# 用户留言

$uId =  $_REQUEST['toId'];

$sql = "INSERT INTO " . getTName('qqfarm_message') ." (`toID`, `toName`, `fromID`, `fromName`, `msg`, `time`, `isReply`) VALUES (" . $_REQUEST['toId'] . ", '". qf_getUserName($_REQUEST['toId'])."', " . $_QFG['uid'] . ", '" . $_REQUEST['fName'] . "', '".$_REQUEST['msg']."'," . $_QFG['timestamp'] .", ".$_REQUEST['isReply'].")";
$_QFG['db']->query($sql);

$query = $_QFG['db']->query("SELECT * FROM " . getTName("qqfarm_message") . " WHERE toID = " . $_QFG['uid'] . " ORDER BY time DESC limit 0,50");
while($value = $_QFG['db']->fetch_array($query)) {
	if($value['fromID'] == $value['toID']){
		$value["fromName"] = $value["toName"] = "主人";
	}
	if($chat) {
		$chat .= ',{"fromId":'.$value["fromID"].',"fromName":"'.$value["fromName"].'","toId":'.$uId.',"toName":"'.qf_getUserName($uId).'","time":'.$value["time"].',"msg":"'.$value["msg"].'","isReply":'.$value["isReply"].'}';
	} else {
		$chat = '{"fromId":'.$value["fromID"].',"fromName":"'.$value["fromName"].'","toId":'.$uId.',"toName":"'.qf_getUserName($uId).'","time":'.$value["time"].',"msg":"'.$value["msg"].'","isReply":'.$value["isReply"].'}';
	} 
}

echo '{"code":1,"chat":['.$chat.']}';

?>