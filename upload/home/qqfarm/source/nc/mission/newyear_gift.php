<?php

#新年红包

$redpackage = rand(1,100);
if($redpackage < 3) {
	if($redpackage < 2) {
		$red_gift = "\"gift\":{\"direction\":\"您打开了农场新年红包，获得以下奖励：\",\"item\":[{\"eNum\":3,\"eParam\":2,\"eType\":3},{\"eNum\":3,\"eParam\":111,\"eType\":1},{\"eNum\":200,\"eParam\":1,\"eType\":7}],\"title\":\"农场新年红包\"},";
		$bluerose = 111;
		$huafei = 2;
		$packagearr[$bluerose] += 3;
		$toolsarr[$huafei] += 3;
		$exp_str=$exp_str+200;
	} else {
		$red_gift = "\"gift\":{\"direction\":\"您打开了农场新年红包，获得以下奖励：\",\"item\":[{\"eNum\":1,\"eParam\":3,\"eType\":3},{\"eNum\":2,\"eParam\":114,\"eType\":1},{\"eNum\":1,\"eParam\":9001,\"eType\":909090}],\"title\":\"农场新年红包\"},";
		$bluerose = 114;
		$huafei = 3;
		$packagearr[$bluerose] += 2;
		$toolsarr[$huafei] += 1;
		if($_QFG['timestamp'] < $dogarr['dogFeedTime']) {
			$dogarr['dogFeedTime'] += 3600*24;
		} else {
			$dogarr['dogFeedTime'] = $_QFG['timestamp']+3600*24;
		}
	}
} else {
	$red_gift = '';
}

?>