<?php

# 新手任务提示

$taskid = $_QFG['db']->result($_QFG['db']->query("SELECT taskid FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
echo '{"taskId":' . $taskid . '}';

?>
