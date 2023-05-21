<?php

# 农场杀虫

$query = $_QFG['db']->query("SELECT Status,tips,pest,dog FROM " . getTName("qqfarm_nc") . " where uid=" . intval($_REQUEST['ownerId']));
while($value = $_QFG['db']->fetch_array($query)) {
	$list[] = $value;
}
$farmarr = qf_decode($list[0][Status]);
$farmpest = qf_decode($list[0][pest]);

if(intval($_REQUEST['ownerId']) != $_QFG['uid']) {
	$Tips = qf_decode($list[0]['tips']);
}

$ZONG = $_QFG['db']->result($_QFG['db']->query("SELECT zong FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);

$pieces = explode(",", $_REQUEST['place']);
foreach($pieces as $pid) {
	if($_REQUEST['tId'] == 0) //普通杀虫
		{
		$g = $farmarr[$pid]['g'];
		$farm_arr = $farmarr[$pid]['p'];
		if($g > 0) { //farmpest
			$cnt_all = array_count_values((array)$farmpest[$pid]);
			$cnt_you = $cnt_all[$_QFG['uid']];
			if($g <= $cnt_you) {
				echo '{"code":0,"direction":"证据是不能毁灭的！","farmlandIndex":' . $pid . ',"poptype":1,"pest":' . $g . '}';
				exit();
			} else {
				unset($farmpest[$pid][$g]);
				if($g == 1) {
					unset($farmpest[$pid]);
				}
			}
			$g -= 1;
			if($ZONG > 150) { //限制除草
				$echo_str[] = '{"code":1,"direction":"' . $Tips['pest_b'] . '","farmlandIndex":' . $pid . ',"poptype":1,"money":2,"exp":0,"levelUp":' . $levelup_arr . ',"pest":' . $farmarr[$pid]['g'] . '}';
			} else {
				$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set exp = exp+2,zong=zong+1 where uid=" . $_QFG['uid']);
				$exp_arr = $_QFG['db']->result($_QFG['db']->query("SELECT exp FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
				$levelup = $_QFG['db']->result($_QFG['db']->query("SELECT levelup FROM " . getTName("qqfarm_nc") . " where uid=" . $_QFG['uid']), 0);
				$levelup_arr = 'false';
				if($exp_arr >= $levelup && $levelup < 93001) {
					include("source/nc/config/levelup.php"); //升级提示
				}
				$echo_str[] = '{"code":1,"direction":"' . $Tips['pest_b'] . '","farmlandIndex":' . $pid . ',"poptype":1,"money":2,"exp":2,"levelUp":false,"pest":' . $farmarr[$pid]['g'] . '}';
			}
		}
		foreach($farm_arr as $key_pw => $value_pw) {
			if($value_pw == 1) {
				unset($farm_arr[$key_pw]);
				break;
			}
		}
		$farmarr[$pid]['p'] = $farm_arr;
		$farmarr[$pid]['g'] = $g;
	} else {
		die('{"tId":' . $_REQUEST['tId'] . ',"farmlandIndex":' . $pid . ',"code":0,"poptype":1,"direction":"' . $Tips['pest_b'] . '"}');
	}
}

$_QFG['db']->query("UPDATE " . getTName("qqfarm_config") . " set money = money + 2 where uid=" . $_QFG['uid']);

if(intval($_REQUEST['ownerId']) == $_QFG['uid']) {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "',pest='" . qf_encode($farmpest) . "' where uid=" . $_QFG['uid']);
	qf_addFeed('farmlandstatus_spraying1');
} else {
	$_QFG['db']->query("UPDATE " . getTName("qqfarm_nc") . " set Status='" . qf_encode(array_values($farmarr)) . "',pest='" . qf_encode($farmpest) . "' where uid=" . $_REQUEST['ownerId']);
	qf_addFeed('farmlandstatus_spraying2');
	//帮忙杀虫日志
	$sql1 = "SELECT `id`, `uid`, `cropid`, `fromid`, `count`,`counts`,`time`, `type` FROM  " . getTName("qqfarm_nclogs") . " WHERE fromid = " . $_QFG['uid'] . " and type=2 and uid = " . $_REQUEST['ownerId'] . " and time > " . ($_QFG['timestamp'] - 3600);
	$query_r = $_QFG['db']->query($sql1);
	$value_r = $_QFG['db']->fetch_array($query_r);
	if($value_r != null) {
		$result[] = $value_r;
                if (strpos($result[0][counts], ':') !== false) {
                    $counts_ = explode(':', $result[0][counts]);
                    $counts_[1]++;
                    $counts_all = join(':', $counts_);
                } else {
                    $counts_all = "0:1:0";
                }
		       $sql = "UPDATE " . getTName("qqfarm_nclogs") . " set count = count+1,counts='{$counts_all}',time = " . $_QFG['timestamp'] . " where id = " . $result[0][id];
     } else {
        $sql = "INSERT INTO " . getTName("qqfarm_nclogs") ." (`uid`, `type`, `count`,`counts`, `fromid`, `time`, `cropid`, `isread` ) VALUES (" . $_REQUEST['ownerId'] . ", 2, 1,'0:1:0', " . $_QFG['uid'] . ", " . $_QFG['timestamp'] . ", 0, 0);";
    }
    $_QFG['db']->query($sql);
}

echo '' . implode(',', $echo_str) . '';

?>