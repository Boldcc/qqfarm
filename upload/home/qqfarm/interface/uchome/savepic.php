<?php

# 发布: 小小宇  &  ︶ㄣ若ヤ海つ
# 链接: http://code.google.com/p/qfarm
# 版本: QQFarm 3.4 Build 2010/03/19 07:30


include_once('../../common.php');
header("Content-Type:text/html; charset=utf-8");

if(!$_SGLOBAL['supe_uid']) {
	die('{"error":"您尚未登陆或登陆已过期,请刷新后重试。","errno":"-900"}');
}

//定义快照保存相册
$FARM_ALBUMNAME = '农场牧场';

//加载UCH图片处理函数
include_once(S_ROOT . './source/function_image.php');

$get_file = sreadfile('php://input');
$filepath = getfilepath('jpg', true);
$new_name = $_SC['attachdir'] . './' . $filepath;

if(swritefile(S_ROOT . $new_name, $get_file)) {
	$tmp_imagesize = @getimagesize(S_ROOT . $new_name);
	list($tmp_width, $tmp_height, $tmp_type) = (array)$tmp_imagesize;
	$tmp_size = $tmp_width * $tmp_height;
	if($tmp_size > 16777216 || $tmp_size < 4 || empty($tmp_type) || strpos($tmp_imagesize['mime'], 'flash') > 0) {
		@unlink(S_ROOT . $new_name);
		echo '{"error":"对不起，保存失败，文件无法保存","errno":"-900"}';
		exit;
	}
	#make thumb
	$thumbpath = @makethumb(S_ROOT . $new_name);
	$thumb = empty($thumbpath) ? 0 : 1;
	#get album
	$albumid = getalbums($_SGLOBAL['supe_uid'], $FARM_ALBUMNAME);
	if(!(intval($albumid) > 0)) {
		#make album
		$setarr = array();
		$setarr['albumname'] = $_SC['dbcharset'] == "utf-8" ? $FARM_ALBUMNAME : iconv("UTF-8", $_SC['dbcharset'], $FARM_ALBUMNAME);
		$setarr['uid'] = $_SGLOBAL['supe_uid'];
		$setarr['username'] = $_SGLOBAL['supe_username'];
		$setarr['dateline'] = $setarr['updatetime'] = $_SGLOBAL['timestamp'];
		$setarr['friend'] = 0;
		$setarr['password'] = "";
		$setarr['target_ids'] = "";
		$setarr['picnum'] = 1;
		$setarr['pic'] = $filepath . ".thumb.jpg";
		$setarr['picflag'] = 1;
		$albumid = inserttable('album', $setarr, 1);
	} else {
		$albumtop = $filepath . ".thumb.jpg";
		$_SGLOBAL['db']->query("UPDATE " . tname("album") . " set picnum=picnum+1,pic='{$albumtop}' where albumid={$albumid}");
	}
	#insert photo
	$picarr['albumid'] = $albumid;
	$picarr['uid'] = $_SGLOBAL['supe_uid'];
	$picarr['dateline'] = $_SGLOBAL['timestamp'];
	$picarr['postip'] = getonlineip();
	$picarr['filename'] = $_SC['dbcharset'] == "utf-8" ? $_GET['picname'] : iconv("UTF-8", $_SC['dbcharset'], $_GET['picname']);
	$picarr['title'] = $_SC['dbcharset'] == "UTF-8" ? $_GET['desc'] : iconv("UTF-8", $_SC['dbcharset'], $_GET['desc']);
	$picarr['type'] = "image/pjpeg";
	$picarr['size'] = $tmp_size;
	$picarr['filepath'] = $filepath;
	$picarr['thumb'] = $thumb;
	$picarr['username'] = $_SGLOBAL['supe_username'];
	$picid = inserttable('pic', $picarr, 1);
	echo '{"url":"../space.php?uid='.$_SGLOBAL['supe_uid'].'&do=album&picid='.$picid.'", "albumid":"'.$albumid.'", "lloc":"M9ovAADVC0KTpe8xDSNj767GjkUUFRYAAA!!"}';
	exit;
} else {
	echo '{"error":"对不起，保存失败，文件无法保存","errno":"-900"}';
	exit;
}


//get upload file name & some path
function getfilepath($fileext, $mkdir = false) {
	global $_SGLOBAL, $_SC;
	$filepath = "{$_SGLOBAL['supe_uid']}_{$_SGLOBAL['timestamp']}" . random(4) . ".{$fileext}";
	$name1 = gmdate('Ym');
	$name2 = gmdate('j');
	if($mkdir) {
		$newfilename = S_ROOT . $_SC['attachdir'] . './' . $name1;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename)) {
				return $filepath;
			}
		}
		$newfilename .= '/' . $name2;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename)) {
				return $name1 . '/' . $filepath;
			}
		}
	}
	return $name1 . '/' . $name2 . '/' . $filepath;
}

//get define albumname's id
function getalbums($uid, $albumname) {
	global $_SGLOBAL;
	$albumid = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT albumid FROM " . tname('album') . " WHERE uid='{$uid}' AND albumname='{$albumname}' ORDER BY albumid ASC LIMIT 1"), 0);
	return $albumid;
}

?>