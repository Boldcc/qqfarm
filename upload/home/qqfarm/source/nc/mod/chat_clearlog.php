<?php

# 清空日志

$query = $_QFG['db']->query("DELETE FROM " . getTName("qqfarm_nclogs") . " where uid = " . $_QFG['uid']);

echo '{"code":1}';

?>