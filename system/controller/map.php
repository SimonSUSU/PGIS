<?
class Map extends ControllersAdmin{
	function __construct($dir = ''){
		parent::__construct($dir);
	}

    public function index($act=''){
        $temp = array();
        $this->load_view('map/jwt', $temp);   
    }


    public function hotel($act=''){
        $temp = array();
        $this->load_view('map/hotel', $temp);   
    }

}
?>