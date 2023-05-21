<?php

# 公共函数库(功能函数)
# create by seaif@zealv.com


/**
 * 将级别转换成经验
 */
function qf_toExp($lv) {
	return intval(pow(($lv + 0.5), 2) * 100 - 25);
}

/**
 * 将经验转换成级别
 */
function qf_toLevel($exp) {
	return floor(sqrt(($exp + 25) / 100) - 0.5);
}

/**
 * 将VIP经验转换成级别
 */
function qf_toVipLevel($exp, $status = 1) {
	global $_VIP;
	$vipExps = (array)$_VIP['exps'];//等级经验表
	if($exp >= end($vipExps)) {
		$lv = count($vipExps);
	} elseif($exp >= reset($vipExps)) {
		foreach($vipExps as $key => $value) {
			if($exp < $value) {
				$lv = $key - 1;
				break;
			}
		}
	}
	$lv = ($status > 0 && $lv > 0) ? (int)$lv : 0;
	return $lv;
}

/**
 * 模板调用
 */
function qf_getView($name) {
	global $_QFG, $_QSC;
	if(!$_QFG['TPL']) {
		include_once('source/template.php');
		$_QFG['TPL'] = STemplate::getInstance($_QSC['view']);
	}
	$_QFG['TPL']->show($name);
}

/**
 * 检查是否已登陆
 *  已登陆: 返回空字符串
 *  未登陆: 返回错误信息
 */
function qf_chkLogin($return = 0) {
	$message = '';
	//检查是否登录
	if($GLOBALS['_QFG']['uid'] <= 0) {
		$message = '请先登录主站.';
	}
	//处理结果
	if($message) {
		$return || die($message);
	}
	return $message;
}

?>