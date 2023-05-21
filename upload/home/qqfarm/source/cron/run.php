<?php

# 计划任务入口
# Modify by seaif@zealv.com

error_reporting(0); //屏蔽所有错误
ignore_user_abort(true); //忽视客户端是否关闭浏览器
set_time_limit(0); //程序执行时间设置为知道程序结束为止
function_exists("date_default_timezone_set") && date_default_timezone_set('PRC');

define('CRON_ROOT', FARM_ROOT . '/source/cron'); //CRON程序路径
define('CRON_DATA', FARM_ROOT . '/data/cron'); //CRON记录路径
define('CRON_LOCK', CRON_DATA . '/run.lock'); //监听锁定
define('CRON_WAIT', 600);//监听时长(单位:秒)

//保证脚本监听只执行一次
if(time() - @filemtime(CRON_LOCK) < CRON_WAIT) {
	die('running...');
} else touch(CRON_LOCK);

//所有计划任务
$crons = array(
	strtotime(date("Y-m-d") . ' 00:00:01') => 'daily',
	strtotime(date("Y-m-d") . ' 06:00:01') => 'weather',
	strtotime(date("Y-m-d") . ' 08:00:01') => 'bad',
	strtotime(date("Y-m-d") . ' 12:00:01') => 'bad',
	strtotime(date("Y-m-d") . ' 16:00:01') => 'bad',
	strtotime(date("Y-m-d") . ' 20:00:01') => 'bad'
);


//执行2个已到期却未执行的任务
if($timeArr = array_filter(array_keys(array_unique($crons)), 'oldTime')) {
	for($i=0; $i<count($timeArr); $i++) {
		if($i>2) { break; }
		$timeOld = $timeArr[$i];
		$cron_file = CRON_ROOT . '/' . $crons[$timeOld] . '.php';
		$cron_over = CRON_DATA . '/' . $timeOld . '.' . $crons[$timeOld];
		if(time() - @filemtime($cron_over) > 86400) {
			touch(CRON_LOCK);//推迟下次监听时间
			if(@include($cron_file)) {
				//echo $crons[$timeOld] . " runned.</br>\r\n";
				touch($cron_over);//记录已执行
				unset($crons[$timeOld]);
			}
		}
	}
}

//等待1个监听期间需要执行的任务
if($timeArr = array_filter(array_keys($crons), 'newTime')) {
	$timeNew = min($timeArr);
	if($timeNew - time() < CRON_WAIT) {
		//echo 'to run ' . $crons[$timeNew];
		sleep($timeNew - time());//等待下一个任务
		touch(CRON_LOCK);//推迟下次监听时间
		$cron_file = CRON_ROOT . '/' . $crons[$timeNew] . '.php';
		$cron_over = CRON_DATA . '/' . $timeNew . '.' . $crons[$timeNew];
		if(@include($cron_file)) {
			touch($cron_over);//记录已执行
		}
	}
}

//过滤器
function oldTime($now) {
	return $now <= time();
}
function newTime($now) {
	return $now > time();
}

?>
