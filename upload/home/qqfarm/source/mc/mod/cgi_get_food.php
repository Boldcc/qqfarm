<?php

# 买草

$food = array();
$food['40'] = array(
	"FBPrice" => 0,
	"consume" => "一只动物每 4 小时消耗 1~5 粒饲料",
	"depict" => "喂养动物(挨饿会停止成长或生产)",
	"effect" => 0,
	"price" => 60,
	"store" => "商店购买牧草后会自动放入饲料机中",
	"tId" => 1,
	"tName" => "牧草",
	"timeLimit" => 0,
	"tip" => "高价购买牧草会消耗较多金币不合算，建议去农场种植。",
	"type" => 25
);
foreach($food as $key => $value) {
	$food_list[] = $value;
}

echo qf_getEchoCode($food_list);

?>