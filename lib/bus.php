<?php
//die("已失效");
//有哪位大神能解释一下那个logid是啥玩意。。。
echo '<br><div class="col-md-8 col-md-offset-2" role="main"><div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">指南</h3></div><div class="panel-body">请先输入链接地址(暂不支持文件夹以及文件夹内文件、加密分享、部分https链接)，点击上面的<b>提交按钮</b>后获得验证码（若无验证码请重新获取），若最后获取到空白请检查验证码是否输入错误或连接是否失效并回去重新获取<br></div></div><center>';
switch(@$_GET["step"]){
case 2:
$url=$_POST["url"];
		echo "您输入的网址:<a href=\"{$url}\">{$url}</a>";
		$ch=curl_init($url);
		curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
		curl_setopt($ch,CURLOPT_HEADER ,1);
		$content=curl_exec($ch);
		curl_close($ch);
	preg_match_all('/window.yunData = (.+?);/iu',$content,$kd);
	$json=json_decode($kd[1][0],1);	$zh=curl_init("http://pan.baidu.com/share/download?bdstoken=null&web=5&app_id=250528&logid={$logid}&channel=chunlei&clienttype=5&uk={$json["uk"]}&shareid={$json["shareid"]}&fid_list=%5B{$json["file_list"][0]["fs_id"]}%5D&sign={$json["downloadsign"]}&timestamp={$json["timestamp"]}");
	curl_setopt($zh,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
	curl_setopt($zh,CURLOPT_RETURNTRANSFER ,1);
	$furljson=curl_exec($zh);
	curl_close($zh);
	$yzm=json_decode($furljson,1);
	if ($yzm["errno"]!=-19){
		echo '<br>验证码获取失败，请刷新本页<br><br>';
	}
	else {
		echo '<br /><img src="data:image/jpg;base64,'.base64_encode(file_get_contents($yzm["img"])).'" width="300" height="90" />';
		echo '<div class="col-md-8 col-md-offset-2" role="main"><form action="./?m=bus&step=3&uk='.$json["uk"].'&shareid='.$json["shareid"].'&fid_list=%5B'.$json["file_list"][0]["fs_id"].'%5D&downloadsign='.$json["downloadsign"].'&timestamp='.$json["timestamp"].'&vcode='.$yzm["vcode"].'" method="post"><div class="input-group"><input type="text" placeholder="请输入图中验证码..." class="form-control" name="input" aria-describedby="sizing-addon1"><span class="input-group-btn"><button class="btn btn-default" type="submit">提交</button></span></div></div></form></div><br>';
}
break;
case 3:
$uk=$_GET["uk"];
	$shareid=$_GET["shareid"];
	$fid_list=$_GET["fid_list"];
	$downloadsign=$_GET["downloadsign"];
	$timestamp=$_GET["timestamp"];
	$vcode=$_GET["vcode"];
	$input=$_POST["input"];
	$url="http://pan.baidu.com/share/download?bdstoken=null&web=5&app_id=250528&logid={$logid}&channel=chunlei&clienttype=5&uk={$uk}&shareid={$shareid}&fid_list={$fid_list}&sign={$downloadsign}&timestamp={$timestamp}&input={$input}&vcode={$vcode}";
	$ch=curl_init($url);
	curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
//curl_setopt($ch,CURLOPT_COOKIE ,'BDUSS='.$_COOKIE["bduss"]);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
	//curl_setopt($ch,CURLOPT_HEADER,1);
	$content=curl_exec($ch);
	curl_close($ch);
	$link=json_decode($content,1);
	//echo $link["dlink"];
	//echo $content;
	$zh=curl_init($link["dlink"]);
	curl_setopt($zh,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
	curl_setopt($zh,CURLOPT_RETURNTRANSFER ,1);
	curl_setopt($zh,CURLOPT_HEADER ,1);
	$link1=curl_exec($zh);
	curl_close($zh);
	preg_match_all("/Location:(.+?)\n/u",$link1,$json);
		echo "<div class=\"col-md-12\" role=\"main\"><div class=\"panel panel-default\"><div class=\"panel-body\">下载地址1:<a href=\"{$json[1][0]}\"\"target=\"_blank\">{$json[1][0]}</a><br></div></div></div><div class=\"col-md-12\" role=\"main\"><div class=\"panel panel-default\"><div class=\"panel-body\">下载地址2:<a href=\"{$link["dlink"]}\"\"target=\"_blank\">{$link["dlink"]}</a><br></div></div></div><br>";
break;
default:
echo '<center><form action="./?m=bus&step=2" method="post"><div class="input-group"><input type="url" placeholder="请输入分享链接..." class="form-control" name="url" aria-describedby="sizing-addon1"><span class="input-group-btn"><button class="btn btn-default" type="submit">提交</button></span></div></div></form></center>';
break;
}
echo '</center>';



/*TODO*/
if (strlen($_POST["spwd"])>0){
		$pwurl=$_POST['url'];
		echo "您输入的网址:<a href=\"{$pwurl}\">{$pwurl}</a>";
		if (!preg_match("/shareid/",$pwurl)){
			$lo_link=curl_init($pwurl);
			curl_setopt($lo_link,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
			curl_setopt($lo_link,CURLOPT_RETURNTRANSFER ,1);
			curl_setopt($lo_link,CURLOPT_HEADER ,1);
			$ct=curl_exec($lo_link);
			curl_close($lo_link);
			preg_match("/Location: (http|https):\/\/pan.baidu.com\/wap\/init\?(.+?)\n/u",$ct,$actbc);
			//var_dump($actbc);
			$pwurll='http://pan.baidu.com/wap/init?'.trim($actbc[2]);
		}else {
			preg_match("/(http|https):\/\/pan.baidu.com\/wap\/init\?(.+?)\n/u",$pwurl."\n",$actbc);
			$pwurll='http://pan.baidu.com/wap/init?'.trim($actbc[2]);
		}
		//echo $actbc[2];
		$post_pwd=curl_init('https://pan.baidu.com/share/verify?'.trim($actbc[2]).'&bdstoken=null&web=5&app_id=250528&logid='.$logid.'&channel=chunlei&clienttype=5');
		curl_setopt($post_pwd,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
		curl_setopt($post_pwd,CURLOPT_RETURNTRANSFER ,1);
		curl_setopt($post_pwd,CURLOPT_POST ,1);
		curl_setopt($post_pwd,CURLOPT_COOKIE ,'BAIDUID=8C43BBA38BC0E3A7E07CA2F06B27554D:FG=1');
		curl_setopt($post_pwd,CURLOPT_REFERER ,$pwurll);
		curl_setopt($post_pwd,CURLOPT_POSTFIELDS ,'pwd='.$_POST["spwd"].'&vcode=&vcode_str=');
		curl_setopt($post_pwd,CURLOPT_HEADER ,1);
		$coo=curl_exec($post_pwd);
		curl_close($post_pwd);
		//echo $coo;
		preg_match_all("/Set-Cookie: (.+?);/",$coo,$jkl);
		//var_dump($jkl);
		$pw_cookie=$jkl[1][0];
		$ch=curl_init($pwurll);
		curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
		curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
		curl_setopt($post_pwd,CURLOPT_REFERER ,$pwurll);
		curl_setopt($ch,CURLOPT_HEADER ,1);
		curl_setopt($ch,CURLOPT_COOKIE ,$pw_cookie.';BAIDUID=F6061AE7720CE80F8AAEB144F895B289:FG=1;PANWEB=1');
		//echo $pw_cookie;
		$content=curl_exec($ch);
		curl_close($ch);
		echo $content;
	}
