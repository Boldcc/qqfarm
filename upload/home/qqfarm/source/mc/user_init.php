<?php

# 初始化新用户数据


	$animal = array(
		array('buyTime'=>$_QFG['timestamp'],'cId'=>1002,'postTime'=>0,'totalCome'=>0,'tou'=>'','growtime'=>165601,'p'=>0),
		array('buyTime'=>$_QFG['timestamp'] , 'cId'=>1002,'postTime'=>0,'totalCome'=>0,'tou'=>'','growtime'=>36001,'p'=>0)
	);
	$taskid = 0;
	$exp =0;
	$feed = array('animalfood'=>20,"animalfeedtime"=>$_QFG['timestamp']);
	$decorative = array('item1'=>1,'item2'=>1,'item3'=>0,'item4'=>1);
	$parade = array('i'=>'2010,我来领导...', 'p'=>0, 'v'=>1);
	$_QFG['db']->query('UPDATE ' . getTName('qqfarm_config') . ' set pf=1 where uid=' . $_QFG['uid']);
	$_QFG['db']->query("INSERT INTO " . getTName('qqfarm_mc') . "(uid,Status,taskid,exp,feed,decorative,parade) VALUES(" . $_QFG['uid'] . ",'" . qf_encode($animal) . "','" . $taskid . "','" . $exp . "','" . qf_encode($feed) . "','" . qf_encode($decorative) . "','" . qf_encode($parade) . "')");


?>