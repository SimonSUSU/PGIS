<?
class Home extends Controllers{
	function __construct($dir = ''){
		parent::__construct($dir);
        $this->cookie_Obj = $this->load_class('cookie');
        $this->user_Obj = $this->load_class_wz('userClass');
	}

    //前台用户退出
    public function logout($type=''){
        $this->cookie_Obj->del(array('WZPOD'));
        msgbox('提示',5,'退出成功！',url(array('home')),5);exit;
    }


    //后台登录、退出方法
    public function index($act=''){
        $login_key = md5($_SERVER['HTTP_USER_AGENT'].ip());//用于保持请求和回调的状态，用于防止csrf攻击（跨站请求伪造攻击）

        switch ($act){

            default:
                if(!empty($this->user_id)){
                   header("Location:/desktop/index/");exit;
                }

                if($_POST){
                    /*
                    $rs = $this->user_Obj->login($_POST);
                    if($rs['code']!='Success'){
                        msgbox('提示',4,$rs['msg'],'',5);exit;
                    }
                    */
                    $rs = $this->user_Obj->uptoken(array('user_id'=>1));
                    $this->cookie_Obj->set(array('WZPOD'=> $rs['item']['token']));
                    //msgbox('提示',1,'验证成功！',url(array('desktop')),5);exit;                    
                    msgbox('提示',1,'验证成功！',url(array('map')),5);exit;
                }               

                $temp = array();
                $this->load_view('login', $temp);
            break;
        }
    }


    //获取验证码
    public function getcode($act=''){
        if(empty($_POST) || empty($_POST['phone'])){
            echo json(array('code' =>'Error', 'msg' => '手机号不能为空'));exit;
        }

        if(!is_phone($_POST['phone'])){
            echo json(array('code' =>'Error', 'msg' => '手机号格式不正确'));exit;
        }
        switch ($act) {
            case 'login':
                $rs = $this->db->select(array('phone' =>$_POST['phone']), 'user_id,status', 'wz_user');
                if(empty($rs)){
                    echo json(array('code' =>'Error', 'msg' => '账户不存在'));exit;
                }
                if($rs[0]['status'] !=1 ){
                    echo json(array('code' =>'Error', 'msg' => '账户未启用，无法发送短信'));exit;
                }      
            break;
            
            default:
                echo json(array('code' =>'Error', 'msg' => '非法请求'));exit;
            break;
        }
        $this->sendsms_Obj = $this->load_class_wz('sendsmsClass');
        $rs = $this->sendsms_Obj->send($_POST);
        echo json($rs);exit;   
    }
    
    
	public function ajaxupload($path=''){
		if($_FILES && $_FILES['filedata'] ){
			$files = $_FILES['filedata'];
			if(!empty($files['tmp_name'])){//当上传的图片不为空时，
	            $uploadObj = $this->load_class('upload');
                if(!empty($path)){
                    $result = $uploadObj->upload_file($files, $path);
                }else{
                    $result = $uploadObj->upload_file($files,'editor');
                }
	            if(!empty($result['code']) && $result['code']=='Error'){
                    $result['message'] = $result['msg'];                    
                    echo json($result); exit;
	                //return $result; //上传错时
	            }
	            if(!empty($result)){
		            $data['error'] = 0;
	                $data['message'] = '上传成功';
	                $data['url'] = $this->img($result, 0, 0);
	            	echo json($data); exit;
	            }
        	}
        }
	}

}
?>