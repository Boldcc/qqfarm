<?php

# 管理首页

$farmTest = array();

$farmTest['服务器平台'][0] = PHP_OS.' / PHP v' .PHP_VERSION;

$farmTest['数据库版本'][0] = mysql_get_server_info();

if(function_exists('json_encode') & function_exists('json_decode')) {
	$farmTest['PHP JSON扩展'][0] = true;
	if(@version_compare(PHP_VERSION, '5.2.1', '<')) {
		$farmTest['PHP JSON扩展'][1] = "由PEAR支持,可能存在缺陷";
	}
} else {
	$farmTest['PHP JSON扩展'][0] = false;
}

qf_getView("admin/home");

?>