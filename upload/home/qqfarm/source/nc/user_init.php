<?php

# 初始化新用户数据

if($pf_str == null) {
	$_QFG['db']->query("INSERT INTO " . getTName("qqfarm_config") . "(uid,username,pf) VALUES(" . $_QFG['uid'] . ",'" . $_QFG['uname'] . "',0)");
}

if($nc_uid == null) {
	$Tips = array(
		'water_b' => '谢谢帮忙，你真是个好人！',
		'weed_b' => '谢谢你，杂草清除干净了！',
		'pest_b' => '谢谢你，害虫清除干净了！',
		'weed_a' => '缺德啊！竟然做这种坏事！',
		'pest_a' => '可恶啊！你真不是个好人！'
	);
	$Status = json_decode('[' .
		'{"a":2,"b":6,"c":0,"d":0,"e":1,"f":0,"g":0,"h":1,"i":100,"j":0,"k":16,"l":9,"m":16,"n":[],"o":0,"p":[],"q":' . ($_QFG['timestamp'] - 36030) . ',"r":1251351720},' .
		'{"a":2,"b":1,"c":0,"d":0,"e":1,"f":1,"g":0,"h":1,"i":100,"j":0,"k":0,"l":0,"m":0,"n":[],"o":0,"p":[],"q":' . ($_QFG['timestamp'] - 14400) . ',"r":1251351725},' .
		'{"a":2,"b":1,"c":0,"d":0,"e":1,"f":0,"g":0,"h":0,"i":100,"j":0,"k":0,"l":0,"m":0,"n":[],"o":0,"p":[],"q":' . ($_QFG['timestamp'] - 14400) .',"r":1251351725},' .
		'{"a":2,"b":1,"c":0,"d":0,"e":1,"f":0,"g":2,"h":1,"i":100,"j":0,"k":0,"l":0,"m":0,"n":[],"o":0,"p":[],"q":' . ($_QFG['timestamp'] - 25200) . ',"r":1251351725},' .
		'{"a":0,"b":0,"c":0,"d":0,"e":1,"f":0,"g":0,"h":1,"i":100,"j":0,"k":0,"l":0,"m":0,"n":[],"o":0,"p":[],"q":0,"r":1251351725},' .
		'{"a":0,"b":0,"c":0,"d":0,"e":1,"f":0,"g":0,"h":1,"i":100,"j":0,"k":0,"l":0,"m":0,"n":[],"o":0,"p":[],"q":0,"r":1251351725}' .
	']');
	$exp = 0;
	$decorative = json_decode('{'.
		'"1":{"1":{"status":1,"validtime":1}},' .
		'"2":{"2":{"status":1,"validtime":1}},' .
		'"3":{"3":{"status":1,"validtime":1}},' .
		'"4":{"4":{"status":1,"validtime":1}}'  .
	'}');
	$healthmode = json_decode('{"beginTime":0,"canClose":1,"date":"1970-01-01|1970-01-07","endTime":0,"serverTime":' . $_QFG['timestamp'] . ',"set":0,"time":"08|00","valid":0}');
	$_QFG['db']->query("INSERT INTO " . getTName("qqfarm_nc") . "(uid,tips,Status,exp,decorative,healthmode) VALUES(" . $_QFG['uid'] . ",'" . qf_encode($Tips) . "','" . qf_encode($Status) . "'," . $exp . ",'" . qf_encode($decorative) . "','" . qf_encode($healthmode) . "')");
	qf_addFeed('user_init');
}

?>