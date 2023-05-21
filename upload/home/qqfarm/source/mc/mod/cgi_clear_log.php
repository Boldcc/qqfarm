<?php

# 清空日志

$query = $_QFG['db']->query("DELETE FROM " . getTName("qqfarm_mclogs") . " where uid = " . $_QFG['uid']);

?>
