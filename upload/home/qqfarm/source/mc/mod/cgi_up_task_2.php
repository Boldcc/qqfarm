<?php

# 新手任务

$taskid = $_QFG['db']->result($_QFG['db']->query('SELECT taskid FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);

if($taskid == 0) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+50 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+50,taskid=1 where uid=' . $_QFG['uid']);
	echo '{"addExp":50,"item":[{"num":50,"type":7},{"num":50,"type":6}],"money":50,"task":{"taskDesc":"恭喜您完成任务，获得50个经验和50个金币！","taskFlag":1,"taskId":1}}';
} elseif($taskid == 1) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+100 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=2 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":100,"type":6}],"money":100,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和100个金币！","taskFlag":1,"taskId":2}}';
}
if($taskid == 2) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+150 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=3 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":150,"type":6}],"money":150,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和150个金币！","taskFlag":1,"taskId":3}}';
} elseif($taskid == 3) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+200 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=4 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":200,"type":6}],"money":200,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和200个金币！","taskFlag":1,"taskId":4}}';
} elseif($taskid == 4) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+250 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=5 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":250,"type":6}],"money":250,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和250个金币！","taskFlag":1,"taskId":5}}';
} elseif($taskid == 5) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+300 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=6 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":300,"type":6}],"money":300,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和300个金币！","taskFlag":1,"taskId":6}}';
} elseif($taskid == 6) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+350 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=7 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":350,"type":6}],"money":350,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和350个金币！","taskFlag":1,"taskId":7}}';
} elseif($taskid == 7) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+400 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=8 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":400,"type":6}],"money":400,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和400个金币！","taskFlag":1,"taskId":8}}';
} elseif($taskid == 8) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+450 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=9 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":450,"type":6}],"money":450,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和450个金币！","taskFlag":1,"taskId":9}}';
} elseif($taskid == 9) {
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set money=money+500 where uid=' . $_QFG['uid']);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_mc') . ' set exp=exp+100,taskid=10 where uid=' . $_QFG['uid']);
	echo '{"addExp":100,"item":[{"num":100,"type":7},{"num":500,"type":6}],"money":500,"task":{"taskDesc":"恭喜您完成任务，获得100个经验和500个金币！","taskFlag":1,"taskId":10}}';
}

?>
