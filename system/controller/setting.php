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

	            'keywords' => $_POST['keywords'],
	            'description' => $_POST['description'],
	            'webIcp' => $_POST['webIcp'],
	        );

	        $edit_rs = $this->db->edit($data, array('id'=>1), 'wz_setting');
	        if(empty($edit_rs)){
	            msgbox('提示',4,'修改不成功','',5);exit;
	        }
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