<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once('../common.php');
header('Content-Type:text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");

qf_chkLogin();


//构造模块名称
$mod = $_REQUEST['mod'] ? $_REQUEST['mod'] : '';

//加载模块
if($mod == 'nmc') {
}

elseif($mod == 'savepic') {
	include_once('uchome/savepic.php');
}

else die('参数错误');

?>