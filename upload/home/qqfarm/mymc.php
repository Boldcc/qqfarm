<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once("common.php");
header('Content-Type:text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");

include_once("source/mc/config/animal.php");
if($qf_chkLogin_msg = qf_chkLogin(1)) {
	die('0|&|' . $qf_chkLogin_msg);
}

$maxuid = $_QFG['db']->result($_QFG['db']->query("SELECT uid FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']), 0);
if($maxuid == null) {
	include_once("source/mc/user_init.php");
}

//定义允许访问的模块
$mod_list = array(
	'cgi_buy_animal', //买动物
	'cgi_clear_log', //清空日志
	'cgi_demolish_pasture', //放蚊子
	'cgi_enter', //牧场初始化
	'cgi_feed_food', //帮自己、好友加草
	'cgi_feed_special', //萝卜饲养
	'cgi_get_animals', //牧场商店
	'cgi_get_exp', //好友动作提示
	'cgi_get_food', //买草
	'cgi_get_notice', //牧场公告
	'cgi_get_repertory', //仓库上锁
	'cgi_get_repertory_animal', //牧场仓库
	'cgi_get_repertory_package', //牧场食物
	'cgi_get_parade', //读取队行
	'cgi_get_user_info', //牧场日志
	'cgi_harvest_product', //动物收成
	'cgi_help_pasture', //拍蚊子和扫便便
	'cgi_post_product', //动物生产
	'cgi_sale_product', //卖出产品
	'cgi_set_parade', //设置队行
	'cgi_steal_product', //偷动物
	'cgi_up_animalhouse', //升级房子-入库
	'cgi_up_animalhouse_query', //升级房子
	'cgi_up_task_1', //新手任务1
	'cgi_up_task_2', //新手任务2
	'chat_clearchat', //清空留言
	'chat_getallinfo', //牧场留言
	'chat_sendchat', //给好友留言
	'friend', //好友列表
	'user_exchange' //牧场消费
);

//特殊mod参数映射
$mod_map = array(
	'cgi_clear_log?' => 'cgi_clear_log',
	'cgi_enter?' => 'cgi_enter',
	'cgi_get_repertory?target=animal' => 'cgi_get_repertory_animal',
	'cgi_get_repertory?target=package' => 'cgi_get_repertory_package',
	'cgi_get_user_info?' => 'cgi_get_user_info'
);

//构造模块名称
$mod_name = $_REQUEST['mod'] ? $_REQUEST['mod'] : '';
if(array_key_exists($mod_name, $mod_map)) {
	$mod_name = $mod_map[$mod_name];
} else {
	$mod_name .= $_REQUEST['act'] ? '_' . $_REQUEST['act'] : '';
}
$mod_name = strtolower($mod_name);

//加载模块
if(in_array($mod_name, $mod_list)) {
	include_once("source/mc/mod/{$mod_name}.php");
} elseif(FARM_DEBUG) {
	error_log($mod_name . "\r\n", 3, 'data/logs/#mcmod_deny.log');
}

?>