<?
class User extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->user_Obj = $this->load_class_wz('userClass');
        $this->purview_Obj = $this->load_class_wz('purviewClass');
        $this->log_Obj = $this->load_class_wz('logClass');
	}

	//列表
	public function index($act=''){
		if($act=='json'){
            $post =array();
            $post['page'] = (!empty($_POST['page']))?$_POST['page']:0;
            $post['pagesize'] =(!empty($_POST['rows']))?$_POST['rows']:'';		

            if(!empty($_POST['timetype']) && $_POST['timetype']=='last_login_time' ){
                $post['start_login_date'] = (!empty($_POST['start_date'])) ? $_POST['start_date']:'';
                $post['end_login_date'] = (!empty($_POST['end_date']))?$_POST['end_date']:'';
            }else{
                $post['start_date'] = (!empty($_POST['start_date'])) ? $_POST['start_date']:'';
                $post['end_date'] = (!empty($_POST['end_date']))?$_POST['end_date']:'';
            }
            $post['status'] = !empty($_POST['status'])?htmlspecialchars($_POST['status']):'';
            $post['sex'] = !empty($_POST['sex'])?htmlspecialchars($_POST['sex']):'';

			$post['purviewgroup_id'] = !empty($_POST['purviewgroup_id'])?htmlspecialchars($_POST['purviewgroup_id']):'';
            $post['keyword'] = !empty($_POST['keyword'])?htmlspecialchars($_POST['keyword']):'';

			$post['sort'] = !empty($_POST['sort'])?htmlspecialchars($_POST['sort']):'';
			$post['order'] = !empty($_POST['order'])?htmlspecialchars($_POST['order']):'';
			$rs = $this->user_Obj->lists($post);
			if($rs['code'] != 'Success'){
                $data = array('total'=>0, 'rows'=>'', 'msg'=>$rs['msg']);
                echo json($data);exit;
            }

            foreach ($rs['item'] as $k => $v){
                if($this->HCTL_url(array('user','edit'))){
            	   $rs['item'][$k]['phone'] = '<a href="javascript:;" onclick="openWin(\''.url(array('user','edit',$v['user_id'])).'\', \'800px\',\'90%\',\'用户档案\');">'.$v['phone'].'</a>'; 
                }        
            	$rs['item'][$k]['purviewgroup_id'] = !empty($v['purviewgroup_arr']['name']) ? $v['purviewgroup_arr']['name']:'';
                
            	
                if($this->HCTL_url(array('user','set_field'))){
            		$rs['item'][$k]['status_str'] = '<span class="pointer" onclick="set_field('.$v['user_id'].',\'status\');" id="status'.$v['user_id'].'">'.$v['status_str'].'</span>';
                }
                
			}
			$data = array('total'=>$rs['page']['toall'], 'rows'=>$rs['item']);
            echo json($data);exit;
		}

		$temp =array();
		$purviewgroup_rs = $this->purview_Obj->group_lists(array('page'=>0,'pagesize'=>99999999),'purviewgroup_id,name');
		if($purviewgroup_rs['code']=='Success'){
			$temp['purviewgroup_rs'] = $purviewgroup_rs['item'];
		}
        $this->load_view('user/index', $temp);
	}

    public function add(){
        if($_POST){
            $rs = $this->user_Obj->add($_POST, $_FILES);
            if($rs['code']=='Success'){
                //增加操作日志 开始
                $log_arr = array(
                    //'front_content'=>'',//修改前
                    'after_content'=>$_POST,//修改后
                    'user_id'=>$this->user_id,
                    'tb_name'=>'wz_user',
                    'action'=>'add'
                );          
                $this->log_Obj->post_log_add($log_arr);
                //增加操作日志 结束 
                msgbox('提示',2,'添加成功！',url(array('user','index')),5);exit;
            }else{
                msgbox('提示',4,$rs['msg'],'',5);exit;
            }
        }
        $temp = array();
        $purviewgroup_rs = $this->purview_Obj->group_lists(array('page'=>0,'pagesize'=>99999999),'purviewgroup_id,name');
        if($purviewgroup_rs['code']=='Success'){
            $temp['purviewgroup_rs'] = $purviewgroup_rs['item'];
        }
        $this->load_view('user/addEdit', $temp);
    }

    public function edit($user_id=''){
        $rs = $this->user_Obj->view(array('user_id' =>$user_id,'imgWidth'=>640,'imgHeight'=>0));
        if($rs['code']!='Success'){
            msgbox('提示',4,$rs['msg'],'',5);exit;
        }

        if($_POST){
            //if($user_id ==1){
            //    msgbox('提示',4,'维护账号，不可修改','',5);exit;
            //}
            $_POST['user_id'] =$user_id;
            $edit_rs = $this->user_Obj->edit($_POST, $_FILES);
            if($edit_rs['code']=='Success'){
                //增加操作日志 开始
                $log_arr = array(
                    'front_content'=>$rs['item'],//修改前
                    'after_content'=>$_POST,//修改后
                    'user_id'=>$this->user_id,
                    'tb_name'=>'wz_user',
                    'action'=>'edit'
                );          
                $this->log_Obj->post_log_add($log_arr);
                //增加操作日志 结束
                msgbox('提示',2,'修改成功！',url(array('user','edit',$user_id)),5);exit;
            }else{
                msgbox('提示',4,$edit_rs['msg'],'',5);exit;
            }
        }
        
        $temp['rs'] = $rs['item'];        
        $purviewgroup_rs = $this->purview_Obj->group_lists(array('page'=>0,'pagesize'=>99999999),'purviewgroup_id,name');
        if($purviewgroup_rs['code']=='Success'){
            $temp['purviewgroup_rs'] = $purviewgroup_rs['item'];
        }       
        $temp['user_id'] = $user_id;
        $this->load_view('user/addEdit', $temp);
    }


	public function set_field(){
    	if(empty($_POST)){
    		echo json(array('code' =>'Error', 'msg' => '非法请求')); exit;
    	}
		$rs = $this->user_Obj->view(array('user_id'=>$_POST['user_id']),'user_id,status,is_villager,is_villagecadre,is_towncadre,is_platform,is_pricebureau,is_finance');
		if($rs['code'] !='Success'){
			echo json($rs); exit;
		}

        $edit_data_arr = array(
            'user_id' =>$rs['item']['user_id']
        );

        switch ($_POST['field']) {
            case 'status':
                if($rs['item']['status']==1){
                    $edit_data_arr['status']=2;
                    $info = $this->status_arr[2];
                }else{
                    $edit_data_arr['status']=1;
                    $info = $this->status_arr[1];
                }
            break;

            case 'is_villager':
                if($rs['item']['is_villager']==1){
                    $edit_data_arr['is_villager']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_villager']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;

            /* 2019-4-15 由区域工作人员管理产生
            case 'is_villagecadre':
                if($rs['item']['is_villagecadre']==1){
                    $edit_data_arr['is_villagecadre']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_villagecadre']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;

            case 'is_towncadre':
                if($rs['item']['is_towncadre']==1){
                    $edit_data_arr['is_towncadre']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_towncadre']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;
            */

            case 'is_platform':
                if($rs['item']['is_platform']==1){
                    $edit_data_arr['is_platform']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_platform']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;

            case 'is_pricebureau':
                if($rs['item']['is_pricebureau']==1){
                    $edit_data_arr['is_pricebureau']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_pricebureau']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;

            case 'is_finance':
                if($rs['item']['is_finance']==1){
                    $edit_data_arr['is_finance']=2;
                    $info = $this->user_attribute_arr[2];
                }else{
                    $edit_data_arr['is_finance']=1;
                    $info = $this->user_attribute_arr[1];
                }
            break;
            
            default:
                echo json(array('code' =>'Error', 'msg' => '非法请求')); exit;
            break;
        }

		//if($_POST['user_id']==1){
		//	echo json(array('code' =>'Error', 'msg' => '维护账号，不可修改')); exit;
    	//}
		
		$edit_rs = $this->user_Obj->edit($edit_data_arr);
		if($edit_rs['code'] == 'Success'){
			$edit_rs['item']=$info;

            //增加操作日志 开始
            $log_arr = array(
                'front_content'=>$rs['item'],//修改前
                'after_content'=>$edit_data_arr,//修改后
                'user_id'=>$this->user_id,
                'tb_name'=>'wz_user',
                'action'=>'edit'
            );          
            $this->log_Obj->post_log_add($log_arr);
            //增加操作日志 结束

		}
		echo json($edit_rs); exit;
    }

    public function all($act=''){
        if($act=='check' && !empty($_POST) && !empty($_POST['phone'])){
            if(!is_phone($_POST['phone'])){
                echo json(array('code'=>'Error', 'msg'=>'手机号格式不正确'));exit;
            }
            $rs = $this->db->select(array('phone' =>$_POST['phone']), 'user_id,realName', 'wz_user');
            if(!empty($rs)){
                echo json(array('code'=>'Success','item'=>$rs[0], 'msg'=>'用户存在'));exit;
            }else{
                echo json(array('code'=>'Empty', 'msg'=>'用户不存在'));exit;
            }
        }
        $post = array(
           'page'=>1,
           'pagesize'=>100000
        );
        if(!empty($_GET)){
            $_POST = $_GET;
        }
        if(!empty($_POST['status'])){
            $post['status'] = htmlspecialchars($_POST['status']);
        }
        if(!empty($_POST['sex'])){
            $post['sex'] = htmlspecialchars($_POST['sex']);
        }
        if(empty($_POST['area_id']) && !empty($_POST['province'])){
            $post['area_id'] = htmlspecialchars($_POST['province']);
        }else{
            if(!empty($_POST['area_id']) && $_POST['area_id']!='null'){
                $post['area_id'] = !empty($_POST['area_id'])?htmlspecialchars($_POST['area_id']):''; 
            }
        }
        $post['is_householder'] = !empty($_POST['is_householder'])?htmlspecialchars($_POST['is_householder']):'';
        $post['is_villager'] = !empty($_POST['is_villager'])?htmlspecialchars($_POST['is_villager']):'';
        $post['is_villagecadre'] = !empty($_POST['is_villagecadre'])?htmlspecialchars($_POST['is_villagecadre']):'';
        $post['is_towncadre'] = !empty($_POST['is_towncadre'])?htmlspecialchars($_POST['is_towncadre']):'';
        $post['is_platform'] = !empty($_POST['is_platform'])?htmlspecialchars($_POST['is_platform']):'';
        $post['is_pricebureau'] = !empty($_POST['is_pricebureau'])?htmlspecialchars($_POST['is_pricebureau']):'';
        $post['is_finance'] = !empty($_POST['is_finance'])?htmlspecialchars($_POST['is_finance']):'';
        $post['purviewgroup_id'] = !empty($_POST['purviewgroup_id'])?htmlspecialchars($_POST['purviewgroup_id']):'';
        $rs = $this->user_Obj->lists($post,'user_id,realName,phone');
        if($rs['code']=='Success'){
            $data = array();
            foreach ($rs['item'] as $k => $v){
                $data[] = array(
                    'name'=>$v['phone'].'/'.$v['realName'],
                    'user_id'=>$v['user_id'],
                );
            }
            echo json($data);exit;
        }        
    }

}
?>