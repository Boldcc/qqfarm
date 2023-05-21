<?php

# 牧场商店

foreach($animaltype as $key => $value) {
	$shop_list[] = $value;
}

echo qf_getEchoCode($shop_list);

?>