<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


//添加数据
function inserttable($tablename, $insertsqlarr, $returnid = 0, $replace = false, $silent = 0) {
	global $_QFG;
	$insertkeysql = $insertvaluesql = $comma = '';
	foreach((array)$insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma . '`' . $insert_key . '`';
		$insertvaluesql .= $comma . '\'' . $insert_value . '\'';
		$comma = ', ';
	}
	$method = $replace ? 'REPLACE' : 'INSERT';
	$_QFG['db']->query($method . ' INTO ' . getTName($tablename) . ' (' . $insertkeysql . ') VALUES (' . $insertvaluesql . ')', $silent ? 'SILENT' : '');
	if($returnid && !$replace) {
		return $_QFG['db']->insert_id();
	}
}

//更新数据
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent = 0) {
	global $_QFG;
	$setsql = $comma = '';
	foreach((array)$setsqlarr as $set_key => $set_value) {
		$setsql .= $comma . '`' . $set_key . '`' . '=\'' . $set_value . '\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach($wheresqlarr as $key => $value) {
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$_QFG['db']->query('UPDATE ' . getTName($tablename) . ' SET ' . $setsql . ' WHERE ' . $where, $silent ? 'SILENT' : '');
}

//事件发布
function feed_add($icon, $title_template = '', $title_data = array(), $body_template = '', $body_data = array(), $body_general = '', $images = array(), $image_links = array(), $target_ids = '', $friend = '', $appid = '', $returnid = 0) {
	global $_QFG, $_QSC;
	if(empty($appid)) {
		$appid = is_numeric($icon) ? 0 : $_QSC['UC_APPID'];
	}
	$feedarr = array('appid' => $appid, 'icon' => $icon, 'uid' => $_QFG['uid'], 'username' => $_QFG['uname'], 'dateline' => $_QFG['timestamp'], 'title_template' => $title_template, 'body_template' => $body_template, 'body_general' => $body_general, 'image_1' => empty($images[0]) ? '' : $images[0], 'image_1_link' => empty($image_links[0]) ? '' : $image_links[0], 'image_2' => empty($images[1]) ? '' : $images[1], 'image_2_link' => empty($image_links[1]) ? '' : $image_links[1], 'image_3' =>
		empty($images[2]) ? '' : $images[2], 'image_3_link' => empty($image_links[2]) ? '' : $image_links[2], 'image_4' => empty($images[3]) ? '' : $images[3], 'image_4_link' => empty($image_links[3]) ? '' : $image_links[3], 'target_ids' => $target_ids, 'friend' => $friend, 'id' => $id, 'idtype' => $idtype);
	$feedarr = qf_stripslashes($feedarr); //去掉转义
	$feedarr['title_data'] = serialize(qf_stripslashes($title_data)); //数组转化
	$feedarr['body_data'] = serialize(qf_stripslashes($body_data)); //数组转化
	$feedarr['hash_template'] = md5($feedarr['title_template'] . "\t" . $feedarr['body_template']); //喜好hash
	$feedarr['hash_data'] = md5($feedarr['title_template'] . "\t" . $feedarr['title_data'] . "\t" . $feedarr['body_template'] . "\t" . $feedarr['body_data']); //合并hash
	$feedarr = qf_addslashes($feedarr); //增加转义
	//去重
	$query = $_QFG['db']->query("SELECT feedid FROM " . getTName('feed') . " WHERE uid='$feedarr[uid]' AND hash_data='$feedarr[hash_data]' LIMIT 0,1");
	if($oldfeed = $_QFG['db']->fetch_array($query)) {
		updatetable('feed', $feedarr, array('feedid' => $oldfeed['feedid']));
		return 0;
	}
	//插入
	if($returnid) {
		return inserttable('feed', $feedarr, $returnid);
	} else {
		inserttable('feed', $feedarr);
		return 1;
	}
}

//推送接口
function qf_addFeed2($type) {
	global $_QFG;
	$icon = "farm";
	$title_template = $body_general = '';
	$actor = "<a href='space.php?uid={$_QFG['uid']}'>" . $_QFG['uname'] . "</a>";
	if(($toUid = intval($_REQUEST['ownerId'])) > 0) {
		$touser = "<a href='space.php?uid={$toUid}'>" . qf_getUserName($toUid) . "</a>";
	}
	switch($type) {
		case 'user_init':
			$title_template = "{$actor} 添加了 <a href='qqfarm.php'>QQ农场</a> 这个游戏。";
			$body_general = "做农民最光荣，做QQ农场的农民更光荣";
			break;
		case 'landstaus_clearweed1':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstaus_clearweed2':
			$title_template = "{$actor} 去 {$touser} 的 <a href='qqfarm.php'>农场</a> 帮忙。";
			$body_general = "我为人人，人人为我！";
			break;
		case 'farmlandstatus_fertilize':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_harvest':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_planting':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_scarify':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_spraying1':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_spraying2':
			$title_template = "{$actor} 去 {$touser} 的 <a href='qqfarm.php'>农场</a> 帮忙。";
			$body_general = "我为人人，人人为我！";
			break;
		case 'farmlandstatus_water1':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_water2':
			$title_template = "{$actor} 去 {$touser} 的 <a href='qqfarm.php'>农场</a> 帮忙。";
			$body_general = "我为人人，人人为我！";
			break;
		case 'farmlandstatus_sale':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
		case 'farmlandstatus_saleall':
			$title_template = "{$actor} 去自己的 <a href='qqfarm.php'>农场</a> 辛勤工作了一番";
			$body_general = "锄禾日当午，汗滴禾下土，谁知盘中餐，粒粒皆辛苦！";
			break;
	}
	feed_add($icon, $title_template, null, null, null, $body_general);
}

?>