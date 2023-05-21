<?php

# 狗粮商店

include_once("source/nc/config/toolstype.php");

foreach($Toolstype as $key => $value) {
	$Tools[] = $value;
}

echo qf_getEchoCode($Tools);

?>