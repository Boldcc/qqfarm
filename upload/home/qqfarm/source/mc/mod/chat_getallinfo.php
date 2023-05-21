<?php

# 用户留言

$uId =  $_REQUEST['uIdx'];

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