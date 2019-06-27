<?
class Map extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
        $this->area_Obj = $this->load_class_wz('areaClass');
	}

    public function index($area_id=''){
        $temp = array();
        $list_rs = $this->area_Obj->lists(array('page'=>0,'pagesize'=>1000,'status'=>1));
        if($list_rs['code']=='Success'){
            $temp['list_rs'] = $list_rs['item'];
            $temp['rs'] = $list_rs['item'][0];
            if(!empty($area_id)){
                foreach ($list_rs['item'] as $k => $v){
                    if($area_id == $v['area_id']){
                        $temp['rs'] = $v;
                    }
                }
            }
        }
        $this->load_view('map/index', $temp);   
    }
}
?>