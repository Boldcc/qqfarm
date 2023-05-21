<?php

# 作物商店

//隐藏配置
qf_getCache('hide');

foreach($cropstype as $key => $value) {
	if(!in_array($value['cId'], $_HIDE['seed'])) {
		$shop_list[] = $value;
	}
}

echo qf_getEchoCode($shop_list);

?>