<?
class Purviewgroup extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->purview_Obj = $this->load_class_wz('purviewClass');
		$this->log_Obj = $this->load_class_wz('logClass');
	}

	//列表
	public function index($act=''){
		if($act=='json'){
			$post =array();
            $post['page'] = (!empty($_POST['page']))?$_POST['page']:0;
            $post['pagesize'] =(!empty($_POST['rows']))?$_POST['rows']:'';
			$post['keyword'] = !empty($_POST['keyword'])?htmlspecialchars($_POST['keyword']):'';
			$rs = $this->purview_Obj->group_lists($post,'purviewgroup_id,name,sorting,remark,status,add_time,last_time');
			if($rs['code'] != 'Success'){
                $data = array('total'=>0, 'rows'=>'', 'msg'=>$rs['msg']);
                echo json($data);exit;
            }
            foreach ($rs['item'] as $k => $v){
            	if($this->HCTL_url(array('purviewgroup','edit'))){
            		$rs['item'][$k]['name'] ='<a href="javascript:;" onclick="openWin(\''.url(array('purviewgroup','edit',$v['purviewgroup_id'])).'\', \'600px\',\'400px\',\'修改\');">'.$v['name'].'</a>';
					$rs['item'][$k]['edit_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('purviewgroup','edit',$v['purviewgroup_id'])).'\', \'600px\',\'400px\',\'修改\');">修改</a>';
				}
				if($this->HCTL_url(array('purviewgroup','del'))){
					$rs['item'][$k]['del_str']  ='<a href="'.url(array('purviewgroup','del',$v['purviewgroup_id'])).'" onclick="return confirm(\'确定要删除吗？\');">删除</a>';
				}
				
				if($this->HCTL_url(array('purviewgroup','purview'))){
					$rs['item'][$k]['purview_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('purviewgroup','purview',$v['purviewgroup_id'])).'\', \'90%\',\'90%\',\'权限配置\');">权限配置</a>';
				}
			}
			$data = array('total'=>$rs['page']['toall'], 'rows'=>$rs['item']);
            echo json($data);exit;
		}
		$temp = array();
        $this->load_view('purviewgroup/index', $temp);
	}

	//add
	public function add(){
		if($_POST){
			$rs = $this->purview_Obj->group_add($_POST);
			if($rs['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	//'front_content'=>array(),//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purviewgroup',
		        	'action'=>'add'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束
				msgbox('提示',2,'添加成功！',url(array('purviewgroup','index')),3);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',5);exit;
			}
		}
		$temp = array();
        $this->load_view('purviewgroup/addEdit', $temp);
	}

	//edit
	public function edit($purviewgroup_id=''){
		$rs = $this->purview_Obj->group_view(array('purviewgroup_id' =>$purviewgroup_id));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}

		if($_POST){
			$_POST['purviewgroup_id'] =$purviewgroup_id;
			$edit_rs = $this->purview_Obj->group_edit($_POST);
			if($edit_rs['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	'front_content'=>$rs['item'],//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purviewgroup',
		        	'action'=>'edit'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束
				msgbox('提示',2,'修改成功！',url(array('purviewgroup','edit',$purviewgroup_id)),5);exit;
			}else{
				msgbox('提示',4,$edit_rs['msg'],'',5);exit;
			}
		}
		
		$temp['rs'] = $rs['item'];
		$temp['purviewgroup_id'] = $purviewgroup_id;
		$this->load_view('purviewgroup/addEdit', $temp);
	}

	public function del($purviewgroup_id=''){
		$rs = $this->purview_Obj->group_del(array('purviewgroup_id' =>$purviewgroup_id));
		if($rs['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	//'front_content'=>'',//修改前
		        	//'after_content'=>'',//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purviewgroup',
		        	'action'=>'del'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束
			msgbox('提示',1,'删除成功！',url(array('purviewgroup','index')),5);exit;
		}else{
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
	}

	public function purview($purviewgroup_id=''){
		$groupRs = $this->purview_Obj->group_view(array('purviewgroup_id' =>$purviewgroup_id));
		if($groupRs['code']!='Success'){
			msgbox('提示',4,$groupRs['msg'],'',5);exit;
		}

		if(!empty($_POST)){
			$p = empty($_POST['p']) ? '' : implode('|', $_POST['p']);
			$_POST['purview']=$p;
			$_POST['purviewgroup_id']=$purviewgroup_id;
			$rs = $this->purview_Obj->group_edit($_POST);
			if($rs['code']=='Success'){	
				//增加操作日志 开始
		        $log_arr = array(
		        	'front_content'=>$groupRs['item'],//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purviewgroup',
		        	'action'=>'edit'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束		
				msgbox('提示',2,'配置成功！',url(array('purviewgroup','index')),3);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',3);exit;
			}
		}

		
		$groupRs=$groupRs['item'];
		$temp['groupRs']=$groupRs;

		$all_rs = $this->purview_Obj->lists(array('is_system'=>2));
		if($all_rs['code']=='Success'){
			foreach ($all_rs['item'] as $k => $v) {
				$all_rs['item'][$k]['id'] = $v['purview_id'];
			}
			$temp['rs'] = genTree2($all_rs['item']);
			$temp['rsArr'] = empty($groupRs['purview']) ? array() : explode('|', $groupRs['purview']);
		}
		$this->load_view('purviewgroup/purview', $temp);
	}

	public function setStatus(){
    	if(!empty($_POST)){
			$rs = $this->purview_Obj->groupSetStatus($_POST);
			if($rs['code']=='Success'){
				//增加操作日志 开始
		        $log_arr = array(
		        	//'front_content'=>'',//修改前
		        	'after_content'=>$_POST,//修改后
		        	'user_id'=>$this->user_id,
		        	'tb_name'=>'wz_purviewgroup',
		        	'action'=>'edit'
		        );	        
		        $this->log_Obj->post_log_add($log_arr);
		        //增加操作日志 结束
			}
			echo json($rs); exit;
		}else{
			msgbox('提示',4,'非法请求','',5);exit;
		}
    }


	public function all($type=''){
		$post =array(
			'page'=>0,
			'pagesize'=>99999999,
		);
		$rs = $this->purview_Obj->group_lists($post,'purviewgroup_id,name');
		if($rs['code']!='Success'){
			echo json($rs); exit;
		}
		echo json($rs['item']); exit;
	}
	
}
?>