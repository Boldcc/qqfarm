<?php

# 积分兑换
# Modify by seaif@zealv.com

if($_GET['do'] == 'save') {
	//获取当前用户积分
	$credit = qf_userCredit(0);
	$number = $_GET['number'];
	$number = (!is_numeric($number) || $number < 1) ? 0 : intval($number);
	$type = $_GET['type'];
	if($type == "yb") {
		if($number * 10 > $credit)
			die('1|&|你积分不够.');
		elseif($number <= 0)
			die('2|&|输入的数目不能小于或等于0.');
		else {
			qf_userCredit(0, "credit-" . ($number * 10));
			$_QFG['db']->query("UPDATE " . getTName('qqfarm_config') . " set YB=YB+" . $number . " where uid=" . $_QFG['uid']);
			die('3|&|积分成功兑换了.|&|refresh');
		}
	} elseif($type == "jb") {
		if($number > $credit)
			echo '1|&|你积分不够.';
		elseif($number <= 0)
			echo '2|&|输入的数目不能小于或等于0.';
		else {
			qf_userCredit(0, "credit-" . $number);
			$_QFG['db']->query("UPDATE " . getTName('qqfarm_config') . " set money=money+" . ($number * 100) . " where uid=" . $_QFG['uid']);
			die('3|&|积分成功兑换了.|&|refresh');
		}
	}
} else {
	qf_getView("tools/exchange");
}

?>