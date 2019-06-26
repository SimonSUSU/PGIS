<?
class Desktop extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
        $this->cookie_Obj = $this->load_class('cookie');
	}


    //后台登录、退出方法
    public function index($act=''){
        $temp = array();
        $this->load_view('desktop/index', $temp);
   
    }

}
?>