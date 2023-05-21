<?php

# QQFarm System Config [QSC]
# Create by seaif@zealv.com


//QSC初始化
$_QSC = array();

//功能控制开关
$_QSC['ifType'] = 'uchome'; //接口类型
$_QSC['friendType'] = 2; //好友列表类型[1:默认好友,2:全站好友]
$_QSC['missionName'] = '0312'; //当前活动[填写活动名称,留空则关闭]

//模板参数
$_QSC['view']['tplId'] = 'qf_default';//模板名
$_QSC['view']['tplDir'] = 'view/';//模板根目录
$_QSC['view']['cplDir'] = 'data/view/';//编译目录
$_QSC['view']['tplBak'] = 'view/qf_default/';//备用模板
$_QSC['view']['player'] = 1; //音乐播放器[0:关|1:百度播放器]


//加载宿主程序配置,无法加载则使用默认配置
if(!@include_once('interface/' . $_QSC['ifType'] . '/config.php')) {
	$_QSC['dbConf']['dbhost'] = 'localhost'; //MySQL地址
	$_QSC['dbConf']['dbuser'] = 'dbuser'; //MySQL用户名
	$_QSC['dbConf']['dbpwd'] = 'dbpw'; //MySQL密码
	$_QSC['dbConf']['dbname'] = 'dbname'; //数据库名
	$_QSC['dbConf']['charset'] = 'utf8'; //MySQL字符集
	$_QSC['dbConf']['pconnect'] = 0; //是否持续连接
	$_QSC['dbConf']['tbprefix'] = 'qf_'; //表名前缀
}

//重载管理员列表,用于覆盖接口中的配置
$_QSC['adminer'] = $_QSC['adminer']; //[格式为'1,3']

?>