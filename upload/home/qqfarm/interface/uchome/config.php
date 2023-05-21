<?php

# 加载UCH系统配置

if(!@include_once(MAIN_ROOT . '/config.php')) {
	die('Unable to load configuration file.');
}
$_SC || $_SC = $GLOBALS['_SC'];

//管理员列表
$_QSC['adminer'] = $_SC['founder']; //[默认为UCH创始人]

//数据库参数
$_QSC['dbConf']['dbhost'] = $_SC['dbhost']; //MySQL地址
$_QSC['dbConf']['dbuser'] = $_SC['dbuser']; //MySQL用户名
$_QSC['dbConf']['dbpwd'] = $_SC['dbpw']; //MySQL密码
$_QSC['dbConf']['dbname'] = $_SC['dbname']; //数据库名
$_QSC['dbConf']['charset'] = 'utf8'; //MySQL字符集,一般无需修改
$_QSC['dbConf']['pconnect'] = $_SC['pconnect']; //是否持续连接
$_QSC['dbConf']['tbprefix'] = $_SC['tablepre']; //表名前缀

//其他参数
$_QSC['charset'] = $_SC['charset']; //页面字符集
$_QSC['cookiepre'] = $_SC['cookiepre']; //COOKIE前缀
$_QSC['UC_APPID'] = UC_APPID;
$_QSC['UC_KEY'] = UC_KEY;
$_QSC['UC_API'] = UC_API;

?>