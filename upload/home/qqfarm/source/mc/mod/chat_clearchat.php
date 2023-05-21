<?php

# 清空留言

$query = $_QFG['db']->query("DELETE FROM " . getTName("qqfarm_message") . " where toID = " . $_QFG['uid']);

echo '{"code":1}';

?>