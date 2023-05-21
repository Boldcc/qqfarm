<?php

# 系统配置

if($_GET['submit'] == 1) {
	//获取农场牧场公告
	$_QSC['friendType'] = (int)$_POST['friendType'];
	$_QSC['missionName'] = $_POST['missionName'];
	$_QSC['view']['player'] = (int)$_POST['view_player'];
	$_QSC['adminer'] = $_POST['adminer'];
	$_HIDE['seed'] = explode(',', $_POST['hide_seed']);
	$_HIDE['item'] = explode(',', $_POST['hide_item']);
	//保存系统配置
	if(qf_putCache('QSC', $_QSC) && qf_putCache('hide', $_HIDE)) {
		die('1|&|修改成功|&|refresh');
	}
	die('0|&|修改失败');
}

qf_getCache('hide');
qf_getView("admin/system");

?>