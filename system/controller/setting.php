<?
class Setting extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->log_Obj = $this->load_class_wz('logClass');
	}

	public function index(){
		$rs = $this->db->select(' WHERE id=1 ', '*', 'wz_setting');
		if($_POST){

			$data = array(
	            'webName' => $_POST['webName'],
	            'webSize' => $_POST['webSize'],
	            'webInfo' => $_POST['webInfo'],
	            'webEmail' => $_POST['webEmail'],
	            'webTel' => $_POST['webTel'],
	            'webAddr' => $_POST['webAddr'],
	            'webAbout' => $_POST['webAbout'],

	            //'soft_version' => $_POST['soft_version'],

	            'keywords' => $_POST['keywords'],
	            'description' => $_POST['description'],
	            'webIcp' => $_POST['webIcp'],
	        );


			//商品采购审批步骤配置
			if(!empty($_POST['workflow_config_goods'])){
				$workflow_config_goods_arr = explode(',', $_POST['workflow_config_goods']);
				if(empty($workflow_config_goods_arr)){					
					msgbox('提示',4,'商品采购审批步骤，配置格式有误','',5);exit;
				}
				foreach ($workflow_config_goods_arr as $k => $v){
					if(empty($this->workflow_user_type_arr[$v])){
						msgbox('提示',4,'商品采购审批步骤，配置值非系统值','',5);exit;
					}
				}
				$data['workflow_config_goods'] = implode(',',$workflow_config_goods_arr);
			}			


			//问卷得积分审批步骤配置
			if(!empty($_POST['workflow_config_assess'])){
				$workflow_config_assess_arr = explode(',', $_POST['workflow_config_assess']);
				if(empty($workflow_config_assess_arr)){
					msgbox('提示',4,'问卷得积分审批步骤，配置格式有误','',5);exit;
				}
				foreach ($workflow_config_assess_arr as $k => $v){
					if(empty($this->workflow_user_type_arr[$v])){
						msgbox('提示',4,'问卷得积分审批步骤，配置值非系统值','',5);exit;
					}
				}
				$data['workflow_config_assess'] = implode(',',$workflow_config_assess_arr);
			}

			//乡镇自定义问卷审批步骤配置
			if(!empty($_POST['workflow_config_town_assess'])){
				$workflow_config_town_assess_arr = explode(',', $_POST['workflow_config_town_assess']);
				if(empty($workflow_config_town_assess_arr)){
					msgbox('提示',4,'乡镇自定义问卷审批步骤，配置格式有误','',5);exit;
				}
				foreach ($workflow_config_town_assess_arr as $k => $v){
					if(empty($this->workflow_user_type_arr[$v])){
						msgbox('提示',4,'乡镇自定义问卷审批步骤，配置值非系统值','',5);exit;
					}
				}
				$data['workflow_config_town_assess'] = implode(',',$workflow_config_town_assess_arr);
			}


			//超市结算申请审批步骤配置
			if(!empty($_POST['workflow_config_balance'])){
				$workflow_config_balance_arr = explode(',', $_POST['workflow_config_balance']);
				if(empty($workflow_config_balance_arr)){
					msgbox('提示',4,'超市结算申请审批步骤，配置格式有误','',5);exit;
				}
				foreach ($workflow_config_balance_arr as $k => $v){
					if(empty($this->workflow_user_type_arr[$v])){
						msgbox('提示',4,'超市结算申请审批步骤，配置值非系统值','',5);exit;
					}
				}
				$data['workflow_config_balance'] = implode(',',$workflow_config_balance_arr);
			}
			
	        $edit_rs = $this->db->edit($data, array('id'=>1), 'wz_setting');
	        if(empty($edit_rs)){
	            msgbox('提示',4,'修改不成功','',5);exit;
	        }


	        //增加操作日志 开始
	        $data['id'] = 1;
	        $log_arr = array(
	        	'front_content'=>$rs[0],//修改前
	        	'after_content'=>$data,//修改后
	        	'user_id'=>$this->user_id,
	        	'tb_name'=>'wz_setting',
	        	'action'=>'edit'
	        );	        
	        $this->log_Obj->post_log_add($log_arr);
	        //增加操作日志 结束


	        msgbox('提示',5,'修改成功！',url(array('setting','index')),5);exit;
		}
		$temp['rs'] = $rs[0];
        $this->load_view('setting/index', $temp);
	}

	public function flushMemcache(){
		if($this->user_id !=1){
			msgbox('提示',4,'非法请求','',5);exit;	
		}
		$cache_Obj = $this->load_class('WEBcache');
		$cache_Obj->flushCache();
		msgbox('提示',4,'清除成功！','',5);exit;
	}



}
?>