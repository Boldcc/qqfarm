<?php

# 装饰品商店

//隐藏配置
qf_getCache('hide');

foreach($itemtype as $key => $value) {
	if(!in_array($value['itemId'], $_HIDE['item'])){
		$item_list[] = $value;
	}
}

echo qf_getEchoCode($item_list);

?>