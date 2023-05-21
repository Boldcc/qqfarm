<?php

# 新手任务1

$taskid = $_QFG['db']->result($_QFG['db']->query('SELECT taskid FROM ' . getTName('qqfarm_mc') . ' where uid=' . $_QFG['uid']), 0);
echo '{"taskFlag":1,"taskId":' . $taskid . '}';

?>
