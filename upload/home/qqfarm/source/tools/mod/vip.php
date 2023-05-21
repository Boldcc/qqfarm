<?php

# VIP模块(用户UI部分)

//更新自己的VIP参数
$vipme = true;
include_once('source/cron/vip.php');

//VIP配置映射
$vipCost = $_VIP['cost'];
$vipExps = $_VIP['exps'];
$vipSpeed = $_VIP['speed'];
$vipStatus = $_VIP['status'];


if($_POST['payMonth'] > 0) {
	$payMonth = (int)$_POST['payMonth'];
	//计算Y币消费
	$YB = $_QFG['db']->result($_QFG['db']->query("SELECT YB FROM ".getTName( "qqfarm_config" )." where uid=".$_QFG['uid']), 0);
	$needYB = $payMonth == 12 ? $vipCost[2] : $vipCost[1]*$payMonth;
	if($YB < $needYB) {
		die("1|&|对不起，您的Y币余额不足！");
	}
	//转化VIP参数
	$vip['join'] = $_QFG['timestamp']; //续费时间
	if($vip['valid'] >= $_QFG['timestamp']) {
		$vip['valid'] += $payMonth*30*86400; //当前VIP续费
		$payMonth == 12 && $vip['status'] = 2; //是否转年费
		$vMsg = "1|&|续费成功|&|refresh";
	} else {
		$vip['last'] = $_QFG['timestamp'];
		$vip['valid'] = $_QFG['timestamp'] + $payMonth*30*86400; //新开VIP或重新开通
		$vip['status'] = $payMonth == 12 ? 2 : 1; //状态
		$vip['exp'] || $vip['exp'] = 0;
		$vip['rsign'] = 0;
		$vMsg = "2|&|开通成功|&|refresh";
	}
	$_QFG['db']->query("UPDATE ".getTName( "qqfarm_config" )." set YB=YB-".$needYB.",vip='".qf_encode($vip)."' where uid=".$_QFG['uid']);
	die($vMsg);
} else {
	$vip['join']   = (int)$vip['join'];   //续费时间
	$vip['valid']  = (int)$vip['valid'];  //有效时间
	$vip['last']   = (int)$vip['last'];   //上次经验转化时间
	$vip['status'] = (int)$vip['status']; //状态[0:无效|1:月费|2:年费]
	$vip['exp']    = (int)$vip['exp'];    //成长经验
	$vip['rsign']  = (int)$vip['rsign'];  //礼包状态
	$vipExt['level'] = qf_toVipLevel($vip['exp'], $vip['status']);
	$vipExt['valid'] = $vip['valid'] > 0 ? date("Y-m-d", $vip['valid']) : $vipStatus[0];
	$vipExt['status'] = $vip['join'] && !$vip['status'] ? $vipStatus[-1] : $vipStatus[$vip['status']];
	$vipExt['allExp'] = abs(ceil(($vip['valid']-$_QFG['timestamp'])/86400) * $vipExps[$vip['status']]);
	qf_getView("tools/vip");
}

?>