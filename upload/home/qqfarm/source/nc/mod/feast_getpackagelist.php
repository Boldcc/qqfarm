<?php

# 每日礼包
$taskid = $_QFG['db']->result($_QFG['db']->query("SELECT taskid FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
if($taskid == 0) {
	echo '{"direction":"<font color=\"#0099ff\">亲爱的用户'.$_QFG['uname'].'：</font><br>  欢迎你加入《QQ农场》大家庭，我们为你准备了以下礼包：","item":[{"eNum":4,"eParam":1,"eType":3},{"eNum":2,"eParam":7,"eType":1}],"title":"新手礼包","vip":0,"vipItem":"","vipText":"VIP用户应用游戏特权，可享受牧场试用奖励<br>每天登录还可获赠一份惊喜 "}';
	exit();
}

//读VIP级别，根据VIP级别送不同的礼物
$vip = $_QFG['db']->result($_QFG['db']->query("SELECT vip FROM " . getTName("qqfarm_config") . " where uid=" . $_QFG['uid']), 0); 
$vip = qf_decode($vip);
$vip['level'] = qf_toVipLevel($vip['exp']);

switch($vip['level']) {
	case 1:
		$item = '[{"eNum":1,"eParam":41,"eType":1}]';
		break;
	case 2:
		$item = '[{"eNum":3,"eParam":1,"eType":3}]';
		break;
	case 3:
		$item = '[{"eNum":4,"eParam":1,"eType":3}]';
		break;
	case 4:
		$item = '[{"eNum":4,"eParam":1,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	case 5:
		$item = '[{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	case 6:
		$item = '[{"eNum":5,"eParam":1,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	case 7:
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	case 8:
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	case 9:
		$item = '[{"eNum":5,"eParam":3,"eType":3},{"eNum":5,"eParam":2,"eType":3},{"eNum":1,"eParam":9001,"eType":909090}]';
		break;
	default:
		$item = '';
}

echo '{"direction":"您当前领取的是VIP用户每日礼包。<br>您的VIP级别为：</b><font color=\"#CC3300\">' . $vip['level'] . '</font> ，获得以下奖励： <br>","item":' . $item . ',"title":"每日礼包","vip":' . $vip['level'] . ',"vipItem":"","vipText":""}';

?>