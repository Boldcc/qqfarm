<?php

# 公共函数库(扩展函数)
# create by seaif@zealv.com

//读取缓存
function qf_getCache($key) {
	$_tpl = 'data/' . strtolower($key) . '.php';
	$_cpl = 'data/cache/' . strtolower($key) . '.php';
	file_exists($_cpl) || @copy($_tpl, $_cpl);
	@include($_cpl);
	$cName = '_'.strtoupper($key);
	$GLOBALS[$cName] = (array)$$cName;
}

//写入缓存
function qf_putCache($key, $value) {
	$_cpl = 'data/cache/' . strtolower($key) . '.php';
	$cValue = strtoupper($key) . " = " . var_export($value, true);
	return file_put_contents($_cpl, "<?php\r\n\$_" . $cValue . ";\r\n?>");
}

/**
 * 说明: JSON串行化
 *   向客户端输出数据时使用
 */
function qf_getEchoCode($data) {
	$data = json_encode($data);
	$data = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);
	return $data;
}

/**
 * 说明: QF自编码,默认JSON,可选用serialize
 *   把要存入数据库的PHP数据结构串行化
 */
function qf_encode($data) {
	$data = json_encode($data);
	$data = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);
	return $data;
}
/**
 * 说明: QF自解码,默认JSON,可选用unserialize
 *   从数据库取出数据时还原PHP数据结构
 */
function qf_decode($data) {
	return (array)json_decode($data, true);
}

/**
 * 基础函数: addslashes()
 * 功能描述: 添加字符串或数组转义
 */
function qf_addslashes($value) {
	if(is_array($value)) {
		foreach($value as $k => $v) {
			$value[$k] = qf_addslashes($v);
		}
		return $value;
	}
	return addslashes($value);
}

/**
 * 基础函数: stripslashes()
 * 功能描述: 取消字符串或数组转义
 */
function qf_stripslashes($value) {
	if(is_array($value)) {
		foreach($value as $k => $v) {
			$value[$k] = qf_stripslashes($v);
		}
		return $value;
	}
	return stripslashes($value);
}

/**
 * 错误捕获函数
 */
function qf_error_handler($errno, $errstr, $errfile, $errline) {
	if(in_array($errno, array(8, 1024, 8192))) return;//忽略一些错误
	$time = date("Y/m/d h:i:s");
	$errdata =
		"Message: [{$time}] {$_SERVER['REQUEST_URI']} \r\n" .
		"  Error: {$errstr} \r\n" .
		"         on line $errline in $errfile \r\n" .
		"  Errno: {$errno} \r\n \r\n";
	error_log($errdata, 3, "data/logs/#php_error.log");
}

/**
 * 替换SQL关键字
 */
function qf_dowith_sql($str) {
	$str = str_replace("and", "", $str);
	$str = str_replace("execute", "", $str);
	$str = str_replace("update", "", $str);
	$str = str_replace("count", "", $str);
	$str = str_replace("chr", "", $str);
	$str = str_replace("mid", "", $str);
	$str = str_replace("master", "", $str);
	$str = str_replace("truncate", "", $str);
	$str = str_replace("char", "", $str);
	$str = str_replace("declare", "", $str);
	$str = str_replace("select", "", $str);
	$str = str_replace("create", "", $str);
	$str = str_replace("delete", "", $str);
	$str = str_replace("insert", "", $str);
	$str = str_replace("'", "", $str);
	$str = str_replace(" ", "", $str);
	$str = str_replace("or", "", $str);
	$str = str_replace("=", "", $str);
	$str = str_replace("%20", "", $str);
	return $str;
}

?>