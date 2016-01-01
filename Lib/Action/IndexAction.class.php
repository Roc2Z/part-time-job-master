<?php
/**
 * IndexAction
 * 首页 获取mx_jobs表中的兼职列表
 *
 */
class IndexAction extends Action{
	/**
	 *
	 * 默认30分钟内添加的显示 NEW 徽章
	 */
	public function index(){
		//设置城市
		if(!session('?city')){
			session('city','烟台市');
		}
		//自动登录
		if(!session('?username')){
			$this->UserAuto();
			
		}
		//$this->UserAuto();
		//$this->UserLogin();
		
		//设置分类排序
		$order_flag = $this->_get('sort');
		$order = "ctime desc";
		$order_flag_first_num = substr($order_flag,0,1);
		$order_flag_last_num  = substr($order_flag,1,1);
		$arr_sort = array('ctime'=>10,'money'=>30);
		switch ($order_flag_first_num){
			case 1:
				$order = 'ctime';
				break;
			case 2:
				$order = 'address';
				break;
			case 3:
				$order = 'money';
				break;
			case 4:
				$order = 'pv';
				break;
			default:
				 
		}
		if(substr($order_flag,1,1) == 1){
			$order .= ' desc';
		}
		if($order_flag_last_num == 1){
			$arr_sort[$order] = $order_flag_first_num . 0;
		}elseif($order_flag_last_num == 0){
			$arr_sort[$order] = $order_flag_first_num . 1;
		}
		
		$this->assign("arr_sort",$arr_sort);

		$Jobs = M('Jobs');
		//获取当前城市
		//$city = session('?city') ? session('city') : '烟台市';

		$Jobs->query("SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'");
		import('ORG.Util.Page');
		//限制显示未过期的，通过的，当前城市的兼职
		$where = "(" . time() . "- expire_time)<0" . " AND " . "is_pass=0";
		$count = $Jobs->where($where)->count();
		$Page  = new Page($count,10);
		$list  = $Jobs->order('pv desc')->order($order/*.","."ctime desc"*/)
		->limit($Page->firstRow.','.$Page->listRows)
		->field("jid,title,money,money_style,want_peo,begin_time,current_peo,addressname,pv,ctime")
		->where($where)
		->select();
		//设置分页样式
		//		$Page->setConfig('header','条');
		//		$Page->setConfig('prev', '&laquo;');
		//		$Page->setConfig('next', '&raquo;');
		$show = $Page->show();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();

	}
	protected function autoLogin() {
		//判断客户端是否设置了cookie
		if(cookie('userphone') && cookie('utype') && cookie('xmf')){
			$phone  = cookie('userphone');
			$passwd = cookie('xmf');
			$arr;
			//判断
			if(cookie('utype') == 'user'){
				$User = M('Users');
				$arr = $User->where('phone=' . $phone)->field('passwd,uid,username')->find();
				if($passwd == md5($arr['passwd'] . 'xiaomifeng')){
					session('oid',null);
					session('orgname',null);
					session('uid',$arr['uid']);
					session('username',$arr['username']);
				}
			}elseif(cookie('utype') == 'org'){
				$Org = M('Orgs');
				$arr = $Org->where('login_phone=' . $phone)->field('passwd,oid,orgname')->find();
				if($passwd == md5($arr['passwd'] . 'xiaomifeng')){
					session('oid',$arr['oid']);
					session('orgname',$arr['orgname']);
					session('uid',null);
					session('username',null);
				}
				
			}
			
		}
		
		
		
	}
	
	function UserAuto(){
		define("APPID", "wx4afc293b9b16c550");
		define("APPSECRET", "3c94b7c07c340d9ad13b0dbfdb8b24e5");
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
		$data['username'] = $result2['nickname'];
		$data['openid'] = $result2['openid'];
		//$data['phone_num']  = "13212310526";
		//$data['passwd'] = "123";
		//$data['school']      = "鲁东大学";
		$data['ctime']  = time();
		
		//var_dump($data['openid']);
		$Reger = D('Users');
		if($Reger->where(array("openid"=>$data['openid']))->count()){
			//存在
			$expire = 3600*24*3;
			$arr = $Reger->where(array("openid"=>$data['openid']))->field('uid,username')->find();
			session('uid',$arr['uid']);
			session('username',$arr['username']);
			cookie('uid',$arr['uid'],$expire);
			cookie('username',$arr['username'],$expire);
			
		}else{
			//不存在
			if($Reger->create($data,1)){
				if($primary_id = $Reger->add()){
					
					//设置session
					$expire = 3600*24*3;
					session('uid',$primary_id);
					session('username',$data['username']);
					session('openid',$data['openid']);
					cookie('uid',$primary_id,$expire);
					cookie('username',$data['username'],$expire);
					cookie('openid',$data['openid'],$expire);
					
					
					//$this->ajaxReturn(0,"注册成功，等待跳转",$f);
				}
			}
     
		}
		
		
	}
}
?>