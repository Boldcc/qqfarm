<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once("common.php");
header('Content-Type:text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");

//登陆检查
if($qf_chkLogin_msg = qf_chkLogin(1)) {
	die('0|&|' . $qf_chkLogin_msg);
}

//新用户检查
$pf_str = $_QFG['db']->result($_QFG['db']->query("SELECT uid FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0);
$nc_uid = $_QFG['db']->result($_QFG['db']->query("SELECT uid FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
if($pf_str == null || $nc_uid == null) {
	include_once("source/nc/user_init.php");
}

//定义允许访问的模块
$mod_list = array(
	'chat_clearchat', //清空留言
	'chat_clearlog', //清空日志
	'chat_getallinfo', //用户状态
	'chat_sendchat', //用户留言
	'dog_feedmoney', //狗粮提示
	'farmlandstatus_clearweed', //作物除草
	'farmlandstatus_fertilize', //作物加肥
	'farmlandstatus_getoutput', //作物输出
	'farmlandstatus_harvest', //收获作物
	'farmlandstatus_pest', //恶意放虫
	'farmlandstatus_planting', //播种作物
	'farmlandstatus_scarify', //起地作物
	'farmlandstatus_scatterseed', //恶意种草
	'farmlandstatus_scrounge', //偷取作物
	'farmlandstatus_spraying', //作物杀虫
	'farmlandstatus_water', //作物浇水
	'feast_getpackagelist', //礼包提示
	'feast_getpackage', //每日礼包
	'feast_levelup', //升级提示
	'friend', //好友列表
	'friend_1-3', //好友状态
	'gb_buy', //y币购买
	'item_activeitem', //农场装饰
	'item_buy', //金币购买装饰
	'item_deactiveitem', //取消装饰
	'item_getuseritems', //初始化装饰
	'item_healthmode', //健康模式
	'item_shop', //装饰品商店
	'pf_ok', //开通牧场
	'repertory_buyseed', //作物购买
	'repertory_getseedinfo', //作物商店
	'repertory_getusercrop', //用户仓库
	'repertory_getuserseed', //用户包果
	'repertory_sale', //单个卖出
	'repertory_saleall', //全部卖出
	'task_accept', //新手任务提示
	'task_npc', //NPC任务
	'task_update', //新手任务
	'user_card', //赠花留言
	'user_case', //加工厂
	'user_checkstatus', //备用
	'user_exchange', //种子消费
	'user_getnotice', //农场公告
	'user_received', //收到花的信息
	'user_reclaimpay', //开垦土地
	'user_reclaim', //开地提示
	'user_run', //访问自己和别人农场
	'user_send', //送花信息
	'user_received', //收到花的信息
	'user_welcome', //农场欢迎
	'usertool_buytool', //狗粮购买
	'usertool_gettools' //狗粮商店
);


//构造模块名称
if($_REQUEST['cmd'] == "1" || $_REQUEST['cmd'] == "3") {
	$mod_name = "friend_1-3"; //好友状态
} else {
	$mod_name = $_REQUEST['mod'] ? $_REQUEST['mod'] : '';
	$mod_name .= $_REQUEST['act'] ? '_' . $_REQUEST['act'] : '';
}
$mod_name = strtolower($mod_name);

//加载模块
if(in_array($mod_name, $mod_list)) {
	include_once("source/nc/config/farm.php");
	include_once("source/nc/mod/{$mod_name}.php");
} elseif(FARM_DEBUG) {
	error_log($mod_name . "\r\n", 3, 'data/logs/#ncmod_deny.log');
}

?>