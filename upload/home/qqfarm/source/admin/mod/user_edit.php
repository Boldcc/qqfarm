<?php

$id = intval($_GET['id']);
if($id < 1) {
	die('2|&|参数错误.');
}

if($_GET['submit'] == 1) {
	$nc_reclaim = intval($_REQUEST['nc_reclaim']);
	$nc_exp = intval($_REQUEST['nc_exp']);
	$mc_exp = intval($_REQUEST['mc_exp']);
	$money = intval($_REQUEST['money']);
	$YB = intval($_REQUEST['YB']);
	if($nc_reclaim < 1 || $nc_reclaim > 18) {
		die('1|&|修改失败,农田数目应大于1且小于19.');
	} 
	//修改farmlandstatus数据 -- START --
	$query = $_QFG['db']->query("SELECT Status,vip FROM " . getTName("qqfarm_nc") ." n left join ".getTName('qqfarm_config')." c on n.uid=c.uid where n.uid=" . $id);
	while($value = $_QFG['db']->fetch_array($query)) {
		$list[] = $value;
	}
	$Status = qf_decode($list[0][Status]);
	$vip = qf_decode($list[0][vip]);
	$vip['exp'] = intval($_REQUEST['vip']);
	$vip['status'] = intval($_REQUEST['vipstatus']);
	$vip['valid'] = strtotime(trim($_REQUEST['vipvalid']));
	//获取实际开垦农田数
	$farmlandCount = count($Status);
	//添加需开垦的农田
	if($farmlandCount < $nc_reclaim) {
		for($i = $farmlandCount; $i < $nc_reclaim; $i++) {
			$Status[$i] = array("a"=>0,"b"=>0,"c"=>0,"d"=>0,"e"=>1,"f"=>0,"g"=>0,"h"=>1,"i"=>100,"j"=>0,"k"=>0,"l"=>0,"m"=>0,"n"=>array(),"o"=>0,"p"=>array(),"q"=>0,"r"=>1251351725);
		}
	}
	//删除多开垦的农田
	elseif($farmlandCount > $nc_reclaim) {
		foreach($Status as $k => $v) {
			if($k >= $nc_reclaim) {
				unset($Status[$k]);
			}
		}
	}
	//修改farmlandstatus数据 -- OVER --
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_config') . " set YB=" . $YB . ",money=" . $money . ", vip='".qf_encode($vip)."' where uid=" . $id);
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_nc') . " set exp=" . $nc_exp . ",reclaim=" . $nc_reclaim . ", Status='" . qf_encode(array_values($Status)) . "' where uid=" . $id);
	$_QFG['db']->query("UPDATE " . getTName('qqfarm_mc') . " set exp=" . $mc_exp . " where uid=" . $id);
	die('1|&|修改用户(UID:' . $id . ')的信息成功.|&|refresh');
} else {
	$query = $_QFG['db']->query(
		"SELECT s.*,c.exp as exp_nc,c.reclaim,d.exp as exp_mc FROM(
			(" . getTName('qqfarm_config') . " s
				LEFT JOIN " . getTName('qqfarm_nc') . " c ON c.uid=s.uid
			) LEFT JOIN " . getTName('qqfarm_mc') . " d ON d.uid=s.uid
		) where s.uid=" . $id
	);
	$value = $_QFG['db']->fetch_array($query);
	$value['level_nc'] = qf_toLevel($value['exp_nc']);//农场等级
	$value['level_mc'] = qf_toLevel($value['exp_mc']);//牧场等级
	$value['username'] = qf_getUserName($id, true);//强制更新实名
	$value['vip'] = qf_decode($value['vip']);
	$value['vip']['level'] = qf_toVipLevel($value['vip']['exp'], $value['vip']['status']);
	$value['vip']['valid'] = date('Y-m-d',$value['vip']['valid']);
	qf_getView("admin/user_edit");
}

?>