<?php

# 快速管理

$go = $_GET['go'];

if($go == "cron_bad") {
	if(@include('source/cron/bad.php')){
		die('1|&|全站使坏成功,你就让大家忙活去吧.');
	}
	else die('0|&|调用计划任务失败.');
} elseif($go == "exchange_clean") {
	$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_nclogs') . " ");
	$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_mclogs') . " ");
	die('1|&|清理消费日志成功.');
} elseif($go == "mc_clean") {
	$_QFG['db']->query("update " . getTName('qqfarm_mc') . " set bad='',dabian=0");
	die('1|&|清理牧场蚊子和便便成功.');
} elseif($go == "repertory_clean") {
	$_QFG['db']->query("update " . getTName('qqfarm_nc') . " set repertory=''");
	$_QFG['db']->query("update " . getTName('qqfarm_mc') . " set repertory=''");
	die('1|&|初始化成果.');
} elseif($go == "healthmode") {
	$hm = '{"beginTime":0,"canClose":1,"date":"1970-01-01|1970-01-07","endTime":0,"serverTime":1266900062,"set":0,"time":"08|00","valid":0}';
	$_QFG['db']->query("update " . getTName('qqfarm_nc') . " set healthmode='{$hm}'");
	die('1|&|修复健康模式成功.');
} elseif($go == "farmland") {
	$_QFG['db']->query("update " . getTName('qqfarm_nc') . " set weed='',pest=''");
	include_once("source/nc/config/farm.php");
	$query = $_QFG['db']->query("SELECT uid, Status, package, reclaim FROM " . getTName("qqfarm_nc"));
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	foreach($list as $key => $value) {
		//修复种子
		$package = qf_decode($value['package']);
		foreach($package as $pk=>$pv) {
			if(!in_array($pk, array_keys($cropstype))){
				unset($package[$pk]);
			}
		}
		//获取农田参数
		$Status = qf_decode($value['Status']);
		//获取实际开垦农田数
		$farmlandCount = count($Status);
		//添加需开垦的农田
		if($farmlandCount < $value['reclaim']) {
			for($i = $farmlandCount; $i < $value[reclaim]; $i++) {
				$Status[$i] = array("a"=>0,"b"=>0,"c"=>0,"d"=>0,"e"=>1,"f"=>0,"g"=>0,"h"=>1,"i"=>100,"j"=>0,"k"=>0,"l"=>0,"m"=>0,"n"=>array(),"o"=>0,"p"=>array(),"q"=>0,"r"=>1251351725);
			}
		}
		//删除多开垦的农田
		elseif($farmlandCount > $value['reclaim']) {
			foreach($Status as $k => $v) {
				if($k >= $value['reclaim']) {
					unset($Status[$k]);
				}
			}
		}
		//保存农田参数
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($Status)) . "',package='" . qf_encode($package) . "' where uid=" . $value['uid']);
	}
	die('1|&|修复农田参数,种子包成功.');
} else {
	qf_getView("admin/quick");
}

?>