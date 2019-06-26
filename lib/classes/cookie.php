<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cookie{
	public $doname;

	function __construct(){
		#$this->doname=$_SERVER['SERVER_NAME'];
		$this->doname = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']: $_SERVER['SERVER_ADDR'];
	}	
	
	public function set($arr,$time=''){
		if(empty($time)){
			$time = time() + 60*60*24;
		}
		foreach($arr as $k => $v){
			setcookie ($k, $v, $time, '/', $this->doname);
		}
		return true;
	}


	public function get($k){
		return $_COOKIE[$k];
	}

	public function del($arr){
		foreach($arr as $k => $v){
			setcookie ($k, '', time() - 24*60*60, '/',$this->doname);
			setcookie ($v, '', time() - 24*60*60, '/',$this->doname);
		}
		return true;
	}

}
?>