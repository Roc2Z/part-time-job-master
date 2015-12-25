<?php
class RegisterAction extends Action{
	public function index(){
		define("APPID", "wxcb036340c9623e17");
		define("APPSECRET", "76c6e7ab89bad1d8f17d905d937b8f93");
		if (isset($_GET['code'])){
			$CODE=$_GET['code'];//获取code
		}else{
			echo "NO CODE";
		} 
		//获取token_access
		$token_access_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".APPSECRET."&code=".$CODE."&grant_type=authorization_code";
		$res = file_get_contents($token_access_url); //获取文件内容或获取网络请求的内容
		$result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
		$access_token = $result['access_token'];//获取access_token的值
		//获取全局token_access的值
		$token_access_all_url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
		$res1 = file_get_contents($token_access_all_url);
		$result1 = json_decode($res1, true);
		$access_token_all = $result1['access_token'];
		$openid = $result['openid'];//获取openid的值
		//$access_token_all; 
		$info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token_all."&openid=".$openid;
		$info = file_get_contents($info_url);
		$result2 = json_decode($info, true);
		$info_name = $result2['nickname'];
		$info_sex = $result2['sex'];
		$info_city = $result2['city'];
		$info_province = $result2['province'];
		$info_country = $result2['country'];
		echo $info_name;
		echo $info_city;
		echo $info_sex;
		echo $info_country;
	}

	
}
?>