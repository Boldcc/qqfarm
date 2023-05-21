<?php

# QQFarm Interface For UCHome 2.0

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


///////////////////////////////系统函数映射////////////////////

//得到表名
function getTName($name) {
	global $_QSC;
	return $_QSC['dbConf']['tbprefix'] . str_replace('qqfarm', 'qqfarm', $name);
}

//事件推送
function qf_addFeed($type) {
	include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'feed.php');
	qf_addFeed2($type);
}

///////////////////////////////常规函数映射////////////////////

//获取个人首页
function qf_getHomePage($uid = 0) {
	global $_QFG;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	return "../space.php?uid=" . $uid;
}

//获取头像地址
function qf_getheadPic($uid = 0, $size = 'small') {
	global $_QFG, $_QSC;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	return $_QSC['UC_API'] . '/avatar.php?uid=' . $uid . '&size=' . $size . '&type=virtual';
}

//获取QQFarm金币和Y币数量
function qf_getMoney($uid = 0) {
	global $_QFG;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	$query = $_QFG['db']->query('select yb,money FROM ' . getTName('qqfarm_config') . ' where uid=' . $uid);
	while($value = $_QFG['db']->fetch_array($query)) {
		$money = $value[money];
		$yb = $value[yb];
	}
	return array((int)$money, (int)$yb);
}

//宿主端用户积分操作
function qf_userCredit($uid = 0, $credit = null) {
	global $_QFG;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	//修改积分
	if($uid > 0 && $credit) {
		return $_QFG['db']->query("UPDATE " . getTName('space') . " set credit=" . $credit . " where uid=" . $uid);
	}
	//获取积分
	else {
		$uid = $uid > 0 ? $uid : $_QFG['uid'];
		return (int)$_QFG['db']->result($_QFG['db']->query('SELECT credit FROM ' . getTName('space') . ' where uid=' . $uid), 0);
	}
}

//获取好友列表
function qf_getFriends($uid = 0) {
	global $_QFG;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	$friend = array();
	$query = $_QFG['db']->query("SELECT fuid FROM " . getTname('friend') . " WHERE uid='{$uid}' AND status='1'");
	while($value = $_QFG['db']->fetch_array($query)) {
		$friend[] = $value['fuid'];
	}
	return implode(',', $friend);
}

//获取用户实名
function qf_getUserName($uid = 0, $update = false) {
	global $_QFG;
	$uid = $uid > 0 ? $uid : $_QFG['uid'];
	//先查询QQFarm数据库
	if(!$update) {
		$username = $_QFG['db']->result($_QFG['db']->query("SELECT username FROM " . getTName("qqfarm_config") . " where uid=" . $uid), 0);
	}
	//再查询宿主端数据库
	if(!$username) {
		$value = $_QFG['db']->fetch_array($_QFG['db']->query("SELECT username,name,namestatus FROM " . getTName("space") . " where uid=" . $uid));
		$username = $value['namestatus'] ? $value['name'] : $value['username'];
		//更新QF数据
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " SET username='" . $username . "' where uid=" . $uid);
	}
	return $username;
}

///////////////////////////////安全验证函数////////////////////

//检查是否登录
function qf_checkauth() {
	global $_QFG, $_QSC;
	if($auth = $_COOKIE[$_QSC['cookiepre'].'auth']) {
		@list($password, $uid) = explode("\t", qf_authcode($auth, 'DECODE', $_QSC['UC_KEY']));
		$_QFG['uid'] = intval($uid);
		if($password && $_QFG['uid']) {
			$query = $_QFG['db']->query("SELECT * FROM " . getTname('session') . " WHERE uid='$_QFG[uid]'");
			if($member = $_QFG['db']->fetch_array($query)) {
				if($member['password'] != $password) {
					$_QFG['uid'] = 0;
				}
			}
		}
	}
	if(!$_QFG['uid']) {//for pw with uch
		include_once(MAIN_ROOT.'/uc_client/client.php');
		function_exists('checkpwauto') && checkpwauto();
	}
	if($_QFG['uid']) {
		$_QFG['uname'] = qf_getUserName($_QFG['uid']);
	}
}

/**
 * 字符串加密以及解密函数
 *
 * @param string $string     原文或者密文
 * @param string $operation  操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key        密钥
 * @param int $expiry        密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string            处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 *  $a = authcode('abc', 'ENCODE', 'key');
 *  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 *  $a = authcode('abc', 'ENCODE', 'key', 3600);
 *  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function qf_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	global $_QSC;
	$ckey_length = 4; //随机密钥长度 取值 0-32;
	//加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	//取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	//当此值为 0 时，则不产生随机密钥
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

?>