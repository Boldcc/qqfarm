<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


@error_reporting(0);

define('FARM_SET', 1); //内部标示符,请勿修改
define('FARM_DEBUG', 2); //调试模式[=0:关闭|>0:记录MySQL错误|=2:记录PHP错误]
define('FARM_VERSION', '4.0 Final Build 20100401.1200'); //系统版本,请勿修改
define('FARM_ROOT', str_replace('\\', '/', dirname(__file__))); //QQFarm路径
define('MAIN_ROOT', dirname(FARM_ROOT)); //宿主程序路径

//for PHP of Version < 5.2.0
if(@version_compare(PHP_VERSION, '5.2.0', '<')) {
	include_once('source/json.php');
}

//加载函数库
include_once('source/common1.func.php');
FARM_DEBUG == 2 && set_error_handler('qf_error_handler');

//禁止魔术引用
set_magic_quotes_runtime(0);
if(get_magic_quotes_gpc() != 0) {
	$_GET = qf_stripslashes($_GET);
	$_POST = qf_stripslashes($_POST);
	$_REQUEST = qf_stripslashes($_REQUEST);
	$_COOKIE = qf_stripslashes($_COOKIE);
}

//加载系统配置
qf_getCache('QSC');
qf_getCache('VIP');
qf_getCache('NOTICE');

//全局变量
$_QFG = array();
$_QFG['uid'] = 0;
$_QFG['timestamp'] = time();

//加载函数库
include_once('source/common2.func.php');
error_reporting(FARM_DEBUG == 3 ? 2037 : 0);

//连接数据库
include_once(FARM_ROOT . '/source/mysql.php');
$_QFG['db'] = new dbstuff($_QSC['dbConf']);

//加载公共接口
include_once('interface/' . $_QSC['ifType'] . '/public.php');
qf_checkauth(); //检查登录状态

?>