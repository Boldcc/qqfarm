<?php

# 新手任务

$query = $_QFG['db']->query("SELECT taskid,exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$taskid = $list[0]['taskid'];

if($taskid >= 0 && $taskid <= 11) {
	$upmoney = $taskid * 50;
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money=money+{$upmoney} where uid=" . $_QFG['uid']);
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp=exp+100,taskid=taskid+1 where uid=" . $_QFG['uid']);
	$taskFlag = 2;
	if($taskid == 11) {
		$taskFlag = 0;
		$taskid = 0;
	}
	echo '{"direction":"恭喜您完成任务,获得100个经验和'.$upmoney.'个金币","item":[{"eType":7,"eParam":0,"eNum":100},{"eType":6,"eParam":0,"eNum":'.$upmoney.'}],"levelUp":false,"task":{"taskId":'.$taskid.',"taskFlag":'.$taskFlag.'}}';
}

?>
