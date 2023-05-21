<?php

# 读取队行

$parade = $_QFG['db']->result($_QFG['db']->query("SELECT parade FROM " . getTName("qqfarm_mc") . " where uid=" . $_QFG['uid']), 0);
echo qf_getEchoCode(qf_decode($parade));

?>