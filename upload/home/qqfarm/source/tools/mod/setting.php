<?php

# 游戏设置
# Modify by seaif@zealv.com

$query = $_QFG['db']->query("select tips FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']);
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$Tips = qf_decode($list[0]['tips']);

if($_GET['do'] == "save") {
	$ncTips = array(
		'water_b' => '谢谢帮忙，你真是个好人！',
		'weed_b' => '谢谢你，杂草清除干净了！',
		'pest_b' => '谢谢你，害虫清除干净了！',
		'weed_a' => '缺德啊！竟然做这种坏事！',
		'pest_a' => '可恶啊！你真不是个好人！'
	);
	$tip_type = $_POST['type'];
	$tip_value = $_POST['value'];
	if(array_key_exists($tip_type, $ncTips)) {
		$Tips[$tip_type] = $_POST['value'] ? $_POST['value'] : $ncTips[$tip_type];
		$result = $_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set tips ='" . qf_encode($Tips) . "' where uid=" . $_QFG['uid']);
		if($result) {
			die('1|&|修改成功.|&|null|&|' . $Tips[$tip_type]);
		} else
			die('2|&|未知异常,保存失败.');
	} else
		die('3|&|提示消息不能为空.');
} else {
	qf_getView("tools/setting");
}

?>