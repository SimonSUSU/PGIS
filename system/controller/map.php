<?
class Map extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
        $this->area_Obj = $this->load_class_wz('areaClass');
	}

    public function index($area_id=''){
        if(!empty($area_id)){
            $rs = $this->area_Obj->view(array('area_id'=>$area_id,'status'=>1));
        }
        if(empty($area_id) || $rs['code']!='Success'){
            $rs = $this->area_Obj->lists(array('page'=>0,'pagesize'=>1,'status'=>1));
            if($rs['code']=='Success'){
               $rs['item'] = $rs['item'][0];
            }
        }
        $temp = array();
        $temp['rs'] = $rs['item'];        
        $this->load_view('map/index', $temp);   
    }

}
?>