<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


//加载UC接口
include_once(MAIN_ROOT . '/uc_client/client.php');

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
	//推送事件到UC
	if($title_template) {
		uc_feed_add($icon, $uid, $username, $title_template, null, null, null, $body_general);
	}
}

?>