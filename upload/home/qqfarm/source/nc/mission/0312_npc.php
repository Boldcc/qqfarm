<?php

# NPC任务::植树活动

//白萝卜:2 玉米:3,番茄:7,南瓜:10,西瓜:14,丝瓜:44,菠萝:27,枇杷:55,木瓜:38,番石榴:79,银杏树:83

$num1 = 2;
$num2 = 14;
$num3 = 7;
$num4 = 27;
$num5 = 38;
$yxsId = 25;
$zs1 = 269;
$zs2 = 270;
$zs3 = 271;
$zs4 = 272;

$chris = $_QFG['db']->result($_QFG['db']->query("SELECT chris FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
if($chris == 4) { //已经领取
	die('{"code":0,"direction":"你已经领了，明年这时候再来吧！！！"}');
}

$query = $_QFG['db']->query("SELECT fruit,package FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}

$fruit = qf_decode($list[0][fruit]);
$package = qf_decode($list[0][package]);

$yxs = 0;
if($fruit[$num1] > 200) {
	$yxs += 10;
	$ok_str = " <font color='#ff0000'><b>白萝卜</b></font> ";
} else {
	$no_str .= " <font color='#ff0000'><b>白萝卜</b></font> ";
}
if($fruit[$num2] > 200) {
	$yxs += 10;
	$ok_str .= " <font color='#ff0000'><b>西瓜</b></font> ";
} else {
	$no_str .= " <font color='#ff0000'><b>西瓜</b></font> ";
}
if($fruit[$num3] > 200) {
	$yxs += 10;
	$ok_str .= " <font color='#ff0000'><b>番茄</b></font> ";
} else {
	$no_str .= " <font color='#ff0000'><b>番茄</b></font> ";
}
if($fruit[$num4] > 200) {
	$yxs += 10;
	$ok_str .= " <font color='#ff0000'><b>菠萝</b></font> ";
} else {
	$no_str .= " <font color='#ff0000'><b>菠萝</b></font> ";
}
if($fruit[$num5] > 200) {
	$yxs += 10;
	$ok_str .= " <font color='#ff0000'><b>木瓜</b></font> ";
} else {
	$no_str .= " <font color='#ff0000'><b>木瓜</b></font> ";
}

//无
if(empty($ok_str)) {
	die('{"code":0,"direction":"对不起，你的仓库中没有春妮指定的秘密作物满足条件！<br /><br />春妮指定的秘密作物分别是<b><font color=\"#ff0000\">白萝卜、番茄、西瓜、菠萝和木瓜</font></b>！只要以上列表中的任意作物在仓库中的数量超过200个，就可以获赠10颗银杏树种子哦！集齐5种活动作物，除了50颗银杏树种子，还将获得农场绿色装扮一套！<br /><br /><a href=\"http://qbar.qq.com/appevent/r/?31\" target=\"_blank\"><font color=\"#ff0000\">更多活动详情&gt;&gt;</font></a><br /><br />领奖时间：3月8日 15点  - 3月10日 15点（每人一次领取机会）"}');

}


if($_REQUEST['confirm']) {
	echo '{"code":1,"direction":"恭喜哦！<br />你成功获得了<font color=\"#ff0000\"><b>银杏树种子' . $yxs . '颗</b></font>。<br /><br />快去培育你的银杏树苗吧！爱护我们的地球，多给她一点绿色！<br /><br />收获银杏树苗时有可能发生一些小惊喜哦！<br />","item":[{"eNum":' . $yxs . ',"eParam":25,"eType":1}]}';
} else {
	if($yxs == 50) {
		//装饰品一套....decorative
		$decorative = $_QFG['db']->result($_QFG['db']->query("SELECT decorative FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
		$decorative = qf_decode($decorative);
		$decorative[$itemtype][$zs1]['itemType'][$zs1][status] = 0;
		$decorative[$itemtype][$zs1]['itemType'][$zs1][validtime] = $_QFG['timestamp'] + $itemtype[$zs1][itemValidTime];
		$decorative[$itemtype][$zs2]['itemType'][$zs2][status] = 0;
		$decorative[$itemtype][$zs2]['itemType'][$zs2][validtime] = $_QFG['timestamp'] + $itemtype[$zs2][itemValidTime];
		$decorative[$itemtype][$zs3]['itemType'][$zs3][status] = 0;
		$decorative[$itemtype][$zs3]['itemType'][$zs3][validtime] = $_QFG['timestamp'] + $itemtype[$zs3][itemValidTime];
		$decorative[$itemtype][$zs4]['itemType'][$zs4][status] = 0;
		$decorative[$itemtype][$zs4]['itemType'][$zs4][validtime] = $_QFG['timestamp'] + $itemtype[$zs4][itemValidTime];
		$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set decorative='" . qf_encode($decorative) . "' where uid=" . $_QFG['uid']);
	}
	$package[$yxsId] += $yxs;
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set  package='" . qf_encode($package) . "', chris=4 where uid=" . $_QFG['uid']);
	echo '{"code":1,"direction":"<br />春妮给你算下！一，二，三... ...<br /><br />你已经集齐了秘密作物中的：' . $ok_str . '。<br />将获<font color=\"#ff0000\"><b>银杏树种子' . $yxs . '颗</b></font>。<br /><br />温馨提示：集齐5种绿色风暴作物，还能额外获赠一套' . $no_str . '不够200个！<br />确定现在领取奖励吗？<font color=\"#ff0000\">机会只有一次</font>哦！"}';
}

?>