<?php

#农场植树活动


$redpackage = rand(1,100);

if($redpackage < 6) {
	$red_gift = '"gift":{"direction":"<font size=\"12\"><font color=\"#FF0000\"></b >植树造林防风固沙</b ></font ></font >\n要抵御风沙，必须造防护林并种上草，以减弱风力，风一旦遇上防护林，速度要减弱75%左右，草也可使风无法吹起砂砾。\n<font color=\"#FF0000\">学习奖励：</font ><font color=\"#FF0000\">1颗风信子</font >","item":[{"eNum":1,"eParam":112,"eType":1}],"title":"农场植树活动"},';
	$bluerose = 112;
	$packagearr[$bluerose] += 1;
} elseif($redpackage < 11) {
	$red_gift = '"gift":{"direction":"<font size=\"12\"><font color=\"#FF0000\"></b >树木是天然蓄水库、天然空调</b ></font ></font >\n城市森林可增加空气湿度，一株成年树，一天可蒸发400公斤水，所以树林中的空气湿度明显上升。据计算，城市绿地面积每增加1%，当地夏季的气温可降低0.1摄氏度。\n<font color=\"#FF0000\">学习奖励：</font ><font color=\"#FF0000\">1颗粉玫瑰种子</font >","item":[{"eNum":1,"eParam":111,"eType":1}],"title":"农场植树活动"},';
	$bluerose = 111;
	$packagearr[$bluerose] += 1;
}
else {
	$red_gift = '';
}

?>