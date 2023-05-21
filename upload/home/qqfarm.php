<?php
# QQFarm interface
# Modify by seaif@zealv.com

include_once("common.php");

$qfCharset = $_SC['charset'] ? strtolower($_SC['charset']) : 'utf-8';
include template('qqfarm/view/if_uchome/main.' . $qfCharset);

?>