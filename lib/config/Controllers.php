<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//前端 不需要身份认证的
class Controllers extends Base{
	public $SYSTEM_CONFIG;

	function __construct($dir = ''){
		$this->db  = Db_pdo::get_instance();
		$cookieObj = $this->load_class('cookie');

		$site_config = site_config();  // 获取配置信息
		$url_key = $site_config['url_key'];

		$rs = $this->db->select(' WHERE 1=1 ', '*', 'wz_setting');
		$this->SYSTEM_CONFIG = $rs[0];


		//用户身份
		if(!empty($_COOKIE['WZPOD'])){
			$cookie_data = diy_encode($_COOKIE['WZPOD'],$url_key,'decode');			
			$cookie_data = explode('|',$cookie_data);
			if(empty($cookie_data[0]) || empty($cookie_data[1]) || empty($cookie_data[2])){
				$cookieObj->del(array('WZPOD'));
				msgbox('提示',5,'身份认证信息已失效，请重新认证',url(array('home')),5); exit;
			}
			$user_id= $cookie_data[0];//用户ID
			$key= $cookie_data[1];//客户端回传的KEY
			$time= $cookie_data[2];//客户端回传的时间截
			$md5_weixin_unionid = !empty($cookie_data[3]) ? $cookie_data[3] :'';//weixin_unionid
			if(md5($user_id.$url_key.$time) != $key){//回传的KEY，与服务端计算结果不相符
				$cookieObj->del(array('WZPOD'));
				msgbox('提示',5,'登录身份已过期',url(array('home')),5); exit;
			}

			if(time() - $time > 60*60*24*7){//TOKEN 7天有效
				$cookieObj->del(array('WZPOD'));
				msgbox('提示',5,'登录身份已过期，请重新登录', url(array('home')),5); exit;
			}
			$rs = $this->db->select(array('status'=>1, 'user_id' =>$user_id), '*', 'wz_user');
	        if(empty($rs)){
	        	$cookieObj->del(array('WZPOD'));
	        	msgbox('提示',5,'用户不存在或未启用！',url(array('home')),5); exit;
	        }

	        if(!empty($md5_weixin_unionid) && empty($rs[0]['weixin_unionid']) ){//登录时有绑定微信，但现在没有绑定微信了，说明被解绑了，所以要重登录
	        	$cookieObj->del(array('WZPOD'));
	        	msgbox('提示',5,'账户已被解绑微信，请重新登录！',url(array('home')),5); exit;
	        }	        

	        if(!empty($rs[0]['purviewgroup_id'])){
	        	$purviewgroup_rs = $this->db->select(array('purviewgroup_id'=>$rs[0]['purviewgroup_id'],'status'=>1), 'name', 'wz_purviewgroup');
	        	if(empty($purviewgroup_rs)){
	        		$cookieObj->del(array('WZPOD'));
	        		msgbox('提示',5,'所属权限组不可用！',url(array('home')),5); exit;
	        	}
	        	$rs[0]['purviewgroup_name'] = $purviewgroup_rs[0]['name'];
	        }

	        //$rs[0]['sex_str'] = $this->sex_arr[$rs[0]['sex']];
	        $this->user_id = $rs[0]['user_id'];
	 	    $this->user_arr = $rs[0];


			//当登录后，在操作继续时，COOKIE每隔5分钟生在一次。防止24小时后自动失效后退出
			if(time() - $time > 60*5 ){
		        $time = time();
		        $token_key = md5($rs[0]['user_id'] . $url_key .$time);
				if(!empty($rs[0]['weixin_unionid'])){
		            $token_key_data = $rs[0]['user_id'].'|'. $token_key.'|'.$time.'|'.md5(md5($rs[0]['weixin_unionid']));
		        }else{
		            $token_key_data = $rs[0]['user_id'].'|'. $token_key.'|'.$time;
		        }        		
		        $token = diy_encode($token_key_data, $url_key);//加密
	            $cookieObj->set(array('WZPOD'=>$token));
	        }
		}
	}

	/* 序号生成规则 */
	public function createSN($type=''){
		switch ($type){
			case 'order'://兑换单
				$sn = '11'.date('ymd-His').'-'. mt_rand(100,999).mt_rand(100,999);
				$rs = $this->db->select(array('ocode' => $sn),'order_id','wz_order');
			break;

			default:
				return json(array('code' => 'Error', 'msg' => '类型未知')); exit;
			break;
		}

		if(!empty($rs)){
			$this->createSN($type);
		}else{
			return $sn;
		}
	}

	//生成微信分享的KEY相关的
    public function signPackage(){
    	$this->cache_Obj = $this->load_class('WEBcache');
    	$this->weixin_config = weixin_config();
    	$this->appid = $this->weixin_config['AppID'];
    	$this->appsecret = $this->weixin_config['AppSecret'];
        $token = $this->cache_Obj->getCache($this->appid.'_tokenData');	//取得TOKEN(有效期7200秒)
        if(empty($token)){
            $token_url ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            $token_arr = json_decode($this->https_request($token_url), true);
            if(!empty($token_arr['access_token'])){
                $token = $token_arr['access_token'];
                $this->cache_Obj->setCache($this->appid.'_tokenData', $token, 0, 3600);//1小时内有效
            }           
        }

        //请求获得jsapi_ticket（有效期7200秒，开发者必须在自己的服务全局缓存jsapi_ticket）
        $jsapi_ticket = $this->cache_Obj->getCache($this->appid.'_jsapi_ticket');
        if(empty($jsapi_ticket)){
            $jsapi_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token.'&type=jsapi';
            $data = json_decode($this->https_request($jsapi_url), true);
            if(!empty($data) && $data['errcode']==0){
                $jsapi_ticket = $data['ticket'];
                $this->cache_Obj->setCache($this->appid.'_jsapi_ticket', $jsapi_ticket, 0, 3600);//1小时内有效   
            }
        }

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = md5(time().time());

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapi_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);
        $signPackage = array(
          "appId"     =>$this->appid,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string,
        );
        return  $signPackage;
    }

    /*
	待办任务提醒：简族
	{{first.DATA}}
	申请人：{{keyword1.DATA}}
	待办类型：{{keyword2.DATA}}
	申请时间：{{keyword3.DATA}}
	申请内容：{{keyword4.DATA}}
	{{remark.DATA}}

	业务消息： 白沙服务号
	{{first.DATA}}
	业务类型：{{keyword1.DATA}}
	业务时间：{{keyword2.DATA}}
	业务内容：{{keyword3.DATA}}
	{{remark.DATA}}
	在发送时，需要将内容中的参数（{{.DATA}}内为参数）赋值替换为需要的信息
	*/
	
	/*
	 *推送微信消息
	 *$user_id:用户ID
	 *$tplid:微信模板ID【template_arr的下标】
	 *$content:消息内容
	 *$url:跳转
	 *$isinser是否把数据插入curllog表用定时任务触发：0不插入 1插入
	 *微信跳转的url地址拼接$this->company_id发送消息的公司ID 用于查看者切换身份。
	 *$logid: 队列表中的ID (用于触发器运算时的更新)
	 */
	public function sendWxMsg($user_id, $tplid=0, $content='', $url='', $isinser=1, $logid=''){	
		$add_data = array(
			'url'=>$url,
			'tplid'=>$tplid,
			'data'=>json_encode($content),
			'add_time'=>time(),
			'send_user'=>$this->user_id,//发送人
			'receive_user'=>$user_id,//接收人
	    );
		if($isinser == 1){//插入数据表
			$data = array(
				'url'=>$url,
				'tplid'=>$tplid,
				'data'=>json_encode($content),
				'add_time'=>time(),
				'send_user'=>$this->user_id,//发送人
				'receive_user'=>$user_id,//接收人
	        );
	        $rs = $this->db->insert($data, 'wz_wxmsg_log');
	        if($rs){
	        	return array('code'=>'Success','msg'=>'写入队列成功！', 'item'=>$rs);
	    	}else{
	    		return array('code'=>'Error','msg'=>'写入队列失败！', 'item'=>$rs);
	    	}
		}else{ //发送，并写日志
			$user_rs = $this->db->select(array('user_id'=>$user_id), 'user_id,weixin_openid', 'wz_user');
			if(empty($user_rs)){ //用户存在，并且OPENID有
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'用户不存在'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'用户不存在');
			}
			$openid = $user_rs[0]['weixin_openid'];

			$this->weixin_config = weixin_config();
			$template_arr=array(
				//0=>'3VtL69HzvRgqu7nt5xr7Ad9LP0HiyhnSIlH4_y1Unp8',//待办任务通知(简族)
				0=>'i8X4W2JAgQuU8muJ8nn8w9p7_2eVcAsCD0lxxRROAiU',//业务通知 （白沙服务号）
			);
			$appid = $this->weixin_config['AppID'];
			$appsecret = $this->weixin_config['AppSecret'];

			//判断三个参数都存在
			if(empty($openid)){
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'当前用户尚未绑定微信'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'当前用户尚未绑定微信');
			}
			if(empty($appid)){
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'appid为空'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'appid为空');
			}
			if(empty($appsecret)){
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'appsecret为空'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'appsecret为空');
			}

			if(empty( $template_arr[$tplid] )){
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'模板不存在'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'模板不存在');
			}
			if($url==''){
				$template=array(
					'touser'=>$openid,
					'template_id'=>$template_arr[$tplid],
					'topcolor'=>"#7B68EE",
					'data'=>$content
				);
			}else{
				$wxurl=$this->weixin_config['WebUrl']."/home/go/?url=".$url;//推送的消息地址通过微信跳转访问，如果绑定过，不用登录可以直接访问
				$template=array(
					'touser'=>$openid,
					'template_id'=>$template_arr[$tplid],
					'url'=>$wxurl,
					'topcolor'=>"#7B68EE",
					'data'=>$content
				);
			}			
			$json_template=json_encode($template);

			$access_token = $this->get_access_token($appid, $appsecret);
			if(empty($access_token)){
				if(!empty($logid)){
					$this->db->edit(array('status'=>4, 'send_time'=>time(), 'res'=>'获取access_token失败！'), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
				}
				return array('code'=>'Error','msg'=>'获取access_token失败！');
			}


			$url2="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
			//写文本日志2015/05/06
			$logpath='./log/sendwxmsg/'.date('Y');
			$mode='a+';
			$logcontent=date('Y-m-d H:i:s').'发送微信消息'.$json_template.' TOKEN：'.$access_token;
			$logfile=date('m-d').'_log.txt';
			createfile($logpath,$logfile,$logcontent,$mode);
			//日志写入结束2015/05/06
			$res=$this->https_request($url2,urldecode($json_template));			
			$obj = json_decode($res,true);
            if($obj['errcode'] ==0){//发送信息时，返回成功，并且找到ID时，才处理
            	if(!empty($logid)){
            		$this->db->edit(array('status'=>2, 'send_time'=>time(), 'res'=>$res), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
            	}else{
					$add_data['status'] = 2;
					$add_data['send_time'] = time();
					$add_data['res'] = $res;
					$this->db->insert($add_data, 'wz_wxmsg_log'); //写入日志表
            	}            	
            	return array('code'=>'Success','msg'=>'发送成功！','item'=>$obj);
            }else{
            	if(!empty($logid)){
            		$this->db->edit(array('status'=>$obj['errcode'], 'send_time'=>time(), 'res'=>$res), array('log_id'=>$logid), 'wz_wxmsg_log');//改状态，避免发送重复
            	}else{
	            	$add_data['status'] = $obj['errcode'];
	            	$add_data['send_time'] = time();
	            	$add_data['res'] = $res;
	            	$this->db->insert($add_data, 'wz_wxmsg_log'); //写入日志表
            	}            	
            	return array('code'=>'Error','msg'=>'发送失败！','item'=>$obj);
            }
		}
	}

	/***************  微信相关函数  ******************/
	//获取access_token
	public function get_access_token($appid,$appsecret,$is_get=''){
		$cache_key=md5($appid.'_'.$appsecret.'_access_token');//缓存字符串
		$this->load_class('WEBcache');
		$mch = new WEBcache();
		$access_token=$mch->getCache($cache_key);
		if(empty($access_token) || !empty($is_get) ){//缓存过期重新获取，若强制刷新获取
			$access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
		    $access_token_json =$this-> https_request($access_token_url);
			$access_token_array = json_decode($access_token_json, true);
			if(!empty($access_token_array['access_token'])){
				$access_token = $access_token_array['access_token'];
				$mch->setCache($cache_key, $access_token, 0, 7140);
			}		   
		}
		return $access_token;
	}
}

//需要用户身份认证的
class ControllersUser extends Controllers{
	function __construct($dir = ''){
		parent::__construct();
		$cookieObj = $this->load_class('cookie');
		$site_config = site_config();  // 获取配置信息

		if(!empty($_SERVER['HTTP_REFERER']) &&  strtolower($site_config['web_size']) ==strtolower('http://'.$_SERVER['HTTP_HOST']) ){
			$url = $_SERVER['HTTP_REFERER'];
        	$cookieObj->set(array('lgu'=>$url),60*5);//登录后的跳转放到COOKIE中
        }
        
		if(empty($this->user_id)){
			msgbox('提示',1,'请登录',url(array('home')),5); exit;
		}

		if(DEBUG!='true'){//系统开关，只有debug是开启时，才进行权限检测运算
			$this->purview_obj = $this->load_class_wz('purviewClass');
			$rs = $this->purview_obj->userAuth(array('purviewgroup_id'=>$this->user_arr['purviewgroup_id']));
			if($rs['code']=='Success'){
				$this->sitePurviewArr = $rs['item'];				
			}
		}
	}

	public function CTL_url($urlStr='', $viewStr='', $styleStr=''){		
		if(DEBUG=='true'){
			echo  '<a href="'.url($urlStr).'" '.$styleStr.'>'.$viewStr.'</a>';
		}elseif(!empty($this->sitePurviewArr)){
			$url2Str = is_array($urlStr) ? substr(url($urlStr), 1) : $urlStr;
			foreach($this->sitePurviewArr as $k => $v){
				if($v == $url2Str){
					echo  '<a href="'.url($urlStr).'" '.$styleStr.'>'.$viewStr.'</a>';
				}
			}
		}
	}

	public function HCTL_url($urlStr=''){
		if(DEBUG=='true'){
			return true;
		}
		if(!empty($this->sitePurviewArr)){
			$urlStr3 = is_array($urlStr) ? substr(url($urlStr), 1) : $urlStr;
			foreach($this->sitePurviewArr as $k => $v){
				if($v == $urlStr3){
					return true;
				}
			}
			return false;
		}
		return false;
	}
}


//平台需要管理员身份认证的
class ControllersAdmin extends Controllers{
	function __construct($dir = ''){
		parent::__construct();

		if( empty($this->user_id) ){
			msgbox('提示',1,'请登录',url(array('home')),5); exit;
		}
		
		if(DEBUG!='true'){//系统开关，只有debug是开启时，才进行权限检测运算
			$this->purview_obj = $this->load_class_wz('purviewClass');
			$rs = $this->purview_obj->userAuth(array('purviewgroup_id'=>$this->user_arr['purviewgroup_id']));
			if($rs['code']!='Success'){
				if($this->user_arr['is_villager']==2){//是村民
					if( isMobile()==true ){
						msgbox('TIP', 1, '没有权限-1', url(array('wx','index')), 10); exit;	//没有权限
					}else{
						msgbox('TIP', 5, '没有权限-1', url(array('wx','index')), 10); exit;	//没有权限
					}
				}else{
					if( isMobile()==true ){
						msgbox('TIP', 1, '没有权限-1', url(array('wx','index')), 10); exit;	//没有权限
					}else{
						msgbox('TIP', 5, '没有权限-1', url(array('home','index')), 10); exit;	//没有权限
					}
				}
				
			}
			$this->sitePurviewArr = $rs['item'];
			if ( !in_array($dir.'/', $this->sitePurviewArr) ){//匹配未找到
				if($this->user_arr['is_villager']==2){//是村民
					if( isMobile()==true ){
						msgbox('TIP', 1, '没有权限-2', url(array('wx','index')), 10); exit;	//没有权限
					}else{
						msgbox('TIP', 5, '没有权限-2', url(array('wx','index')), 10); exit;	//没有权限
					}
				}else{
					if( isMobile()==true ){
						msgbox('TIP', 1, '没有权限-2', url(array('wx','index')), 10); exit;	//没有权限	
					}else{
						msgbox('TIP', 5, '没有权限-2', url(array('home')), 10); exit;	//没有权限	
					}
				}
			}
		}
	}


	public function CTL_url($urlStr='', $viewStr='', $styleStr=''){
		if(DEBUG=='true'){
			echo  '<a href="'.url($urlStr).'" '.$styleStr.'>'.$viewStr.'</a>';
		}else{
			$url2Str = is_array($urlStr) ? substr(url($urlStr), 1) : $urlStr;
			foreach($this->sitePurviewArr as $k => $v){
				if($v == $url2Str){
					echo  '<a href="'.url($urlStr).'" '.$styleStr.'>'.$viewStr.'</a>';
				}
			}
		}
	}

	public function HCTL_url($urlStr=''){
		if(DEBUG=='true'){
			return true;
		}
		$urlStr3 = is_array($urlStr) ? substr(url($urlStr), 1) : $urlStr;
		foreach($this->sitePurviewArr as $k => $v){
			if($v == $urlStr3){
				return true;
			}
		}
		return false;
	}
}
?>