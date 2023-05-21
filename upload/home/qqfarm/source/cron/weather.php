<?php

# 修改农场天气
# Modify by seaif@zealv.com

$dateInfo = getdate();

if($dateInfo['wday'] == 4) {
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_config') . " set tianqi=3 where tianqi<4");//雨天
} else {
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_config') . " set tianqi=1");//晴天
}

?>