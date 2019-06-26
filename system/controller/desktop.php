<?
class Desktop extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
        $this->cookie_Obj = $this->load_class('cookie');
	}

    public function index($act=''){
    	header("Location:/map/index/");exit;

        $temp = array();
        $this->load_view('desktop/index', $temp);
   
    }

}
?>