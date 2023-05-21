<?php

# 公告管理

if($_GET['submit'] == 1) {
	//获取农场牧场公告
	$_NOTICE['mc'] = $_POST['mc'];
	$_NOTICE['nc'] = $_POST['nc'];
	unset($_POST['mc'], $_POST['nc']);
	//获取滚动公告
	$main_text = $main_href = array();
	foreach($_POST as $key => $value) {
		if(strpos($key, 'main_text') === 0) {
			$main_text[] = $value;
		} elseif(strpos($key, 'main_href') === 0) {
			$main_href[] = $value;
		}
	}
	for($i=0; $i<count($main_text); $i++) {
		$_NOTICE['main'][$i]['text'] = $main_text[$i];
		$_NOTICE['main'][$i]['href'] = $main_href[$i];
	}
	//保存公告
	if(qf_putCache('NOTICE', $_NOTICE)) {
		die('1|&|修改成功|&|refresh');
	}
	die('0|&|修改失败');
}

qf_getView("admin/notice");

?>