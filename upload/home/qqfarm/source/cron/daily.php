<?php

# 每天(0点)要初始化的字段：
# Modify by seaif@zealv.com

$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set badnum=50"); //放草、虫子
$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set zong=0 "); //限制150次杀草、虫子
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set sfeedleft=30"); //喂养30个萝卜
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set zong=0 "); //限制打100只蚊子
$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set badnum=0"); //放蚊子25只

//VIP升级&每天礼包
$rsign = true;
include_once('source/cron/vip.php');

?>