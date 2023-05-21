<?php
# 仓库上锁

$cId = $_REQUEST['cId'];
$target = $_REQUEST['target'];

if($target == 'unlock') {
	$mclock = $_QFG['db']->result($_QFG['db']->query('SELECT mclock FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);
	$mclock_arr = explode(',',$mclock);
	$mc_lock = '';
	foreach($mclock_arr as $key=>$value) {
		if($cId == $value) {
			unset($mclock_arr[$key]);
		} else {
			if($mc_lock) {
				$mc_lock .= ",".$value;
			} else {
				$mc_lock =$value;
			}
		}
	}
} else if( $target == 'lock' ) {
	$mc_lock = $_QFG['db']->result($_QFG['db']->query('SELECT mclock FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);

	if($mc_lock) {
		$mc_lock .= ",".$cId;
	} else {
		$mc_lock =$cId;
	}
}

$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set mclock='" . $mc_lock . "' where uid=" . $_QFG['uid']);
echo '{"code":1}';

?>