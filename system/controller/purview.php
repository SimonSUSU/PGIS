<? 
class Purview extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->purview_Obj = $this->load_class_wz('purviewClass');
		$this->log_Obj = $this->load_class_wz('logClass');
	}

	//列表
	public function index($act=''){
		if($act=='json'){
			$rs = $this->purview_Obj->lists(array());
			if($rs['code'] != 'Success'){
                $data = array('total'=>0, 'rows'=>'', 'msg'=>$rs['msg']);
                echo json($data);exit;
            }
            if($this->user_id <=2){
	            foreach ($rs['item'] as $k => $v){
	            	if($this->HCTL_url(array('purview','add'))){
	            		$rs['item'][$k]['add_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('purview','add',$v['purview_id'])).'?path='.$v['url'].'\', \'600px\',\'340px\',\'添加\');">添加下级</a>';
	            	}
	            	if($this->HCTL_url(array( 'purview','edit'))){
						$rs['item'][$k]['edit_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('purview','edit',$v['purview_id'])).'\', \'600px\',\'340px\',\'修改\');">修改</a>';
					}
					if($this->HCTL_url(array('purview','del'))){
						$rs['item'][$k]['del_str']  ='<a href="'.url(array('purview','del',$v['purview_id'])).'" onclick="return confirm(\'确定要删除吗？\');">删除</a>';
					}
				}
			}
			$data = array('total'=>count($rs['item']), 'rows'=>genTree2($rs['item'],'purview_id'));
            echo json($data);exit;
		}

		$temp = array();

		$rs = $this->purview_Obj->lists(array());
		if($rs['code']=='Success'){
			$temp['treeStr'] = $this->purview_Obj->tree($rs['item']);
		}else{
			$temp['treeStr'] = $rs['msg'];
		}
		$this->load_view('purview/index', $temp);
	}
	
	public function add($pid = 0){
		 if($this->user_id >2){
		 	msgbox('提示',4,'系统级配置，暂不开放','',3);exit;
		 }
		if(!empty($_POST)){
			$ret = $this->purview_Obj->add($_POST);
			if($ret['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	'front_content'=>array(),//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purview',
		        	'action'=>'add'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束

				msgbox('提示',2,'添加成功！',url(array('purview','index')),3);exit;
			}else{
				msgbox('提示',4,$ret['msg'],'',3);exit;
			}
		}

		$rs = $this->purview_Obj->lists(array());
		if($rs['code']=='Success'){
			$temp['select'] = $this->purview_Obj->tree($rs['item'], 0, '|', 1, $pid);
		}else{
			$temp['select'] ='';
		}
		$temp['path'] = !empty($_GET['path'])?htmlspecialchars($_GET['path']):'';
		$this->load_view('purview/addEdit', $temp);
	}
	
	public function edit($purview_id =''){
		if($this->user_id > 2){
		 	msgbox('提示',4,'系统级配置，暂不开放','',3);exit;
		}

		$rs = $this->purview_Obj->view(array('purview_id' =>$purview_id));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}

		if(!empty($_POST)){
			$_POST['purview_id'] =$purview_id;
			$edit_rs = $this->purview_Obj->edit($_POST);
			if($edit_rs['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	'front_content'=>$rs['item'],//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purview',
		        	'action'=>'edit'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束
				msgbox('提示',2,'修改成功！',url(array('purview','index')),3);exit;
			}else{
				msgbox('提示',4,$edit_rs['msg'],'',3);exit;
			}
		}
		
		$temp['rs'] = $rs['item'];
		if($rs['item']['pid']>0){
			$pid_rs = $this->purview_Obj->view(array('purview_id' =>$rs['item']['pid']));
			if($pid_rs['code']=='Success'){
				$temp['pid_rs'] = $pid_rs['item'];//取得父级的相关信息
			}
		}

		$rs = $this->purview_Obj->lists(array());
		if($rs['code']=='Success'){
			$temp['select'] = $this->purview_Obj->tree($rs['item'], 0, '|', 1, $temp['rs']['pid']);
		}else{
			$temp['select'] ='';
		}
		$this->load_view('purview/addEdit', $temp);
	}
	
	public function del($purview_id =''){
		if($this->user_id >2){
			msgbox('提示',4,'系统级配置，暂不开放','',3);exit;
		}
		$rs = $this->purview_Obj->del(array('purview_id' =>$purview_id));
		if($rs['code']=='Success'){
			//增加操作日志 开始
	        $log_arr = array(
	        	//'front_content'=>'',//修改前
	        	//'after_content'=>'',//修改后
	        	'user_id'=>$this->user_id,
	        	'tb_name'=>'wz_purview',
	        	'action'=>'del'
	        );	        
	        $this->log_Obj->post_log_add($log_arr);
	        //增加操作日志 结束
			msgbox('提示',5,'删除成功！',url(array('purview','index')),5);exit;
		}else{
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
	}

}