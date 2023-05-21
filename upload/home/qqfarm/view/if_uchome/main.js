/*!
 * 提示: 此文件继承UCH模板中加载的js定义
 */


//菜单选择
function qfMenu(o) {
	if(o) {
		$('myfarm').src = o.href;
		//设置样式
		var thisTab = o.parentNode,
		    lastTab = qfMenu.lastTab || $('tab0');
		lastTab.className = '';
		thisTab.className = 'active';
		qfMenu.lastTab = thisTab;
	}
	else dlBox1_Show();
	return false;
}

/*!
 * 音乐播放器控制
 * height:48|83|230
 */
function loadPlayer(height) {
	if(height > 0) {
		if(!loadPlayer.status) {
			loadPlayer.status = 1;
			$("qfplayer_main").className = 'qfplayer_main';
			$("qfplayer_main").innerHTML = '<div id="mp3Player"></div>';
			swfobject.embedSWF(
				"http://box.baidu.com/widget/flash/list.swf?id=605861&autoPlay=true", 
				"mp3Player", "100%", "100%",
				"9.0.124", "qqfarm/source/script/swfobject/expressInstall.swf",
				{}, {wmode:"opaque", allowscriptaccess:"always"}
			);
		}
		$("qfplayer_main").style.height = height + 'px';
	}
	else {
		loadPlayer.status = 0;
		$("qfplayer_main").className = '';
		$("qfplayer_main").innerHTML = '';
		$("qfplayer_main").style.height = '0px';
	}
}


//////////////////////////////////////////////////////////////////////

var curQFWindow = 'myfarm';
var dlBox = new DialogBox();

//大窗口模式
dlBox.Add({Boxid: 'dlBox1_main'});
dlBox.Drag('dlBox1_head', 'dlBox1_main');
function dlBox1_Show() {
	var docEl = document.documentElement;
	if((docEl.clientWidth > 910 && docEl.clientHeight > 600) 
		|| confirm('提示：\r\n    当前浏览器直接可视区域大小不适合大窗口模式，是否继续？')) {
		dlBox.Show('dlBox1_main');
		$('myfarm_full').src = $('myfarm').src ;
		$('myfarm').src = 'about:blank';
		curQFWindow = 'myfarm_full';
	}
	return false;
}
function dlBox1_Hide() {
	dlBox.Hide('dlBox1_main');
	$('myfarm').src = $('myfarm_full').src;
	$('myfarm_full').src = 'about:blank';
	curQFWindow = 'myfarm';
	return false; 
}

//给好友送花
dlBox.Add({Boxid:'dlBox2_main'}); 
dlBox.Drag('dlBox2_head', 'dlBox2_main'); 
function dlBox2_Show() {
	SQuery.ajax({
		url: 'qqfarm/mync.php?mod=friend&refresh=true',
		success: function(data) {
			data = JSON.parse(data);
			if(typeof data == 'object') {
				var fcode = '<select size="20" id="Friends">';
				for(i in data) {
					fcode += '<option value="'+data[i]['userId']+','+data[i]['userName']+'">'+data[i]['userName']+'</option>';
				}
				fcode += '</select>';
			}
			$("dlBox2_body").innerHTML = fcode || data;
			dlBox.Show('dlBox2_main');
		},
		error: function() {
			$("dlBox2_body").innerHTML = "服务器请求错误.";
			dlBox.Show('dlBox2_main');
		}
	});
}
function dlBox2_Hide() {
	dlBox.Hide('dlBox2_main');
	return false; 
}
function flowerToFriend() {
	var FriendsObj = $('Friends') || {};
	if(FriendsObj.value) {
		var user = FriendsObj.value.split(',');
		window.frames[curQFWindow].findSWF('mync').flowerSetFriend(user[0], user[1]);
	}
	dlBox2_Hide();
}
