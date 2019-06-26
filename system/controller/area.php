<?
class Area extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->area_Obj = $this->load_class_wz('areaClass');
	}

	//列表
	public function index($act=''){
		if($act=='json'){
			$post =array();
            $post['page'] = (!empty($_POST['page']))?$_POST['page']:0;
            $post['pagesize'] =(!empty($_POST['rows']))?$_POST['rows']:'';
            $post['start_date'] = (!empty($_POST['start_date'])) ? $_POST['start_date']:'';
            $post['end_date'] = (!empty($_POST['end_date']))?$_POST['end_date']:'';
			$post['keyword'] = !empty($_POST['keyword'])?htmlspecialchars($_POST['keyword']):'';
			$rs = $this->area_Obj->lists($post);
			if($rs['code'] != 'Success'){
                $data = array('total'=>0, 'rows'=>'', 'msg'=>$rs['msg']);
                echo json($data);exit;
            }
            foreach ($rs['item'] as $k => $v){
            	if($this->HCTL_url(array('area','view'))){
            		$rs['item'][$k]['title'] ='<a href="javascript:;" onclick="openWin(\''.url(array('area','view',$v['area_id'])).'\', \'90%\',\'90%\',\'查看\');">'.$v['name'].'</a>';
					$rs['item'][$k]['view_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('area','view',$v['area_id'])).'\', \'90%\',\'90%\',\'查看\');">查看</a>';
				}
            	if($this->HCTL_url(array('area','edit'))){
					$rs['item'][$k]['edit_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('area','edit',$v['area_id'])).'\', \'90%\',\'90%\',\'修改\');">修改</a>';
				}
				if($this->HCTL_url(array('area','del'))){
					$rs['item'][$k]['del_str']  ='<a href="'.url(array('area','del',$v['area_id'])).'" onclick="return confirm(\'确定要删除吗？\');">删除</a>';
				}
			}
			$data = array('total'=>$rs['page']['toall'], 'rows'=>$rs['item']);
            echo json($data);exit;
		}
		$temp = array();
        $this->load_view('area/index', $temp);
	}

	//add
	public function add(){
		if($_POST){
			$rs = $this->area_Obj->add($_POST,$_FILES);
			if($rs['code']=='Success'){
				msgbox('提示',2,'添加成功！',url(array('area','index')),3);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',5);exit;
			}
		}
		$temp = array();
        $this->load_view('area/addEdit', $temp);
	}

	//edit
	public function edit($area_id=''){
		if($_POST){
			$_POST['area_id'] =$area_id;
			$rs = $this->area_Obj->edit($_POST,$_FILES);
			if($rs['code']=='Success'){
				msgbox('提示',2,'修改成功！',url(array('area','edit',$area_id)),5);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',5);exit;
			}
		}
		$rs = $this->area_Obj->view(array('area_id' =>$area_id));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
		$temp['rs'] = $rs['item'];
		$this->load_view('area/addEdit', $temp);
	}

	public function del($area_id=''){
		$rs = $this->area_Obj->del(array('area_id' =>$area_id));
		if($rs['code']=='Success'){
			msgbox('提示',1,'删除成功！',url(array('area','index')),5);exit;
		}else{
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
	}

	public function view($area_id=''){
		$rs = $this->area_Obj->view(array('area_id' =>$area_id));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
		$temp['rs'] = $rs['item'];
		$this->load_view('area/view', $temp);
	}

	
}
?>