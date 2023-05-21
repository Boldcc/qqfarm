<?php

$id = intval($_GET['id']);
if($id < 1) {
	die('1|&|参数错误. ');
}

$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_config') . " WHERE uid=" . $id);
$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_nc') . " WHERE uid=" . $id);
$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_mc') . " WHERE uid=" . $id);
$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_mclogs') . " WHERE uid=" . $id);
$_QFG['db']->query("DELETE FROM " . getTName('qqfarm_nclogs') . " WHERE uid=" . $id);

die('1|&|删除UID为' . $id . '的用户的农牧场成功.|&|refresh');

?>