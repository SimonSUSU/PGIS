<?
class Tag extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
		$this->tag_Obj = $this->load_class_wz('tagClass');
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
			$post['imgWidth'] = 100;
			$post['imgHeight'] = 100;			
			$rs = $this->tag_Obj->lists($post);
			if($rs['code'] != 'Success'){
                $data = array('total'=>0, 'rows'=>'', 'msg'=>$rs['msg']);
                echo json($data);exit;
            }
            foreach ($rs['item'] as $k => $v){        
            	if($this->HCTL_url(array('tag','edit'))){
            		$rs['item'][$k]['edit_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('tag','edit',$v['tag_id'])).'\', \'90%\',\'90%\',\'修改\');">'.$v['name'].'</a>';
					$rs['item'][$k]['edit_str'] ='<a href="javascript:;" onclick="openWin(\''.url(array('tag','edit',$v['tag_id'])).'\', \'90%\',\'90%\',\'修改\');">修改</a>';
				}
				if($this->HCTL_url(array('tag','del'))){
					$rs['item'][$k]['del_str']  ='<a href="'.url(array('tag','del',$v['tag_id'])).'" onclick="return confirm(\'确定要删除吗？\');">删除</a>';
				}
				$rs['item'][$k]['pic'] = !empty($v['pic']) ? '<img src="'.$v['pic'].'" height="50" />':'';
			}
			$data = array('total'=>$rs['page']['toall'], 'rows'=>$rs['item']);
            echo json($data);exit;
		}
		$temp = array();
        $this->load_view('tag/index', $temp);
	}

	//add
	public function add(){
		if($_POST){
			$rs = $this->tag_Obj->add($_POST,$_FILES);
			if($rs['code']=='Success'){
				msgbox('提示',2,'添加成功！',url(array('tag','index')),3);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',5);exit;
			}
		}
		$temp = array();
        $this->load_view('tag/addEdit', $temp);
	}

	//edit
	public function edit($tag_id=''){
		if($_POST){
			$_POST['tag_id'] =$tag_id;
			$rs = $this->tag_Obj->edit($_POST,$_FILES);
			if($rs['code']=='Success'){
				msgbox('提示',2,'修改成功！',url(array('tag','edit',$tag_id)),5);exit;
			}else{
				msgbox('提示',4,$rs['msg'],'',5);exit;
			}
		}
		$rs = $this->tag_Obj->view(array('tag_id' =>$tag_id,'imgWidth'=>100,'imgHeight'=>100));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
		$temp['rs'] = $rs['item'];
		$this->load_view('tag/addEdit', $temp);
	}

	public function del($tag_id=''){
		$rs = $this->tag_Obj->del(array('tag_id' =>$tag_id));
		if($rs['code']=='Success'){
			msgbox('提示',1,'删除成功！',url(array('tag','index')),5);exit;
		}else{
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
	}

	public function view($tag_id=''){
		$rs = $this->tag_Obj->view(array('tag_id' =>$tag_id));
		if($rs['code']!='Success'){
			msgbox('提示',4,$rs['msg'],'',5);exit;
		}
		$temp['rs'] = $rs['item'];
		$this->load_view('tag/view', $temp);
	}

	
}
?>