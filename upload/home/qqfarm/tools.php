<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once('common.php');
header('Content-Type:text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");

qf_chkLogin();


//定义允许访问的模块
$mod_list = array(
	'exchange', //积分兑换
	'fertilizer', //化肥制作
	'setting', //游戏设置
	'vip' //VIP升级
);

//构造模块名称
$mod_name = $_REQUEST['mod'] ? $_REQUEST['mod'] : '';
$mod_name .= $_REQUEST['act'] ? '_' . $_REQUEST['act'] : '';
$mod_name = strtolower($mod_name);

//加载模块
if(in_array($mod_name, $mod_list)) {
	include_once("source/tools/mod/{$mod_name}.php");
} else
	exit('参数错误');

?>