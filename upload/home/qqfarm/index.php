<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once('common.php');
header('Content-Type:text/html; charset=utf-8');

qf_chkLogin();


//获取参数
$mod = $_REQUEST['mod'] ? $_REQUEST['mod'] : 'nmc';
$type = in_array($_GET['type'], array('nc', 'mc')) ? $_GET['type'] : 'nc';

//加载模块
if($mod == 'nmc') {//农场牧场
	if($_QSC['missionName']) {//NPC任务参数
		include_once("source/nc/mission/{$_QSC['missionName']}_vars.php");
	}
	qf_getView('nmc');
} elseif($mod == 'help') {//用户帮助
	qf_getView('help');
} elseif($mod == 'cron') {//计划任务
	include_once("source/cron/run.php");
} else {//异常
	die('参数错误');
}

?>