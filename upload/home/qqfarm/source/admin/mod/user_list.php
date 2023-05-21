<?php

//分页参数
$psize = 25;
$pid = intval($_GET['pid']);
$pid = $pid < 1 ? 1 : $pid;
$start = ($pid - 1) * $psize;

//处理查询
$purl = "admin.php?mod=user_list";
$qqfarm_config_list = array();
$count = $_QFG['db']->result($_QFG['db']->query("SELECT COUNT(*) FROM " . getTName('qqfarm_config')), 0);
if($count) {
	$query = $_QFG['db']->query(
		"SELECT s.*,c.exp as exp_nc,c.reclaim,d.exp as exp_mc FROM(
			(" . getTName('qqfarm_config') . " s
				LEFT JOIN " . getTName('qqfarm_nc') . " c ON c.uid=s.uid
			) LEFT JOIN " . getTName('qqfarm_mc') . " d ON d.uid=s.uid
		) order by s.id asc LIMIT {$start},{$psize}"
	);
	while($value = $_QFG['db']->fetch_array($query)) {
		$value['level_nc'] = qf_toLevel($value['exp_nc']);
		$value['level_mc'] = qf_toLevel($value['exp_mc']);
		$value['vip'] = qf_decode($value['vip']);
		$value['vip']['level'] = qf_toVipLevel($value['vip']['exp'], $value['vip']['status']);
		$qqfarm_config_list[] = $value;
	}
}

qf_getView("admin/user_list");

?>