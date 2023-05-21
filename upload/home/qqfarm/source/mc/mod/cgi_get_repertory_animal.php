<?php

# 牧场仓库

$mc_package = $_QFG['db']->result($_QFG['db']->query('SELECT package FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);

$mclock = $_QFG['db']->result($_QFG['db']->query('SELECT mclock FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);

$mc_package = qf_decode($mc_package);
$mclock_arr = explode(',',$mclock);

foreach($mc_package as $key => $value) {
	if(0 < $value) {
			if(in_array($key, $mclock_arr)){
				$lock = ',"lock":1';
			} else {
				$lock = '';
			}	
		
		$package[] = '{"amount":' . $value . ',"cId":' . $key . ',"cName":"' . $animalname[$key]['name'] . '"'.$lock.',"lv":' . $animalname[$key]['cLevel'] . ',"price":' . $animalname[$key]['price'] . '}';
	}
}

echo '[' . implode(',', $package) . ']';

?>