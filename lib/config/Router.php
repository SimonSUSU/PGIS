<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Router{
	private $_default;
	private static $s_instance;	
	public static $s_directory;
	public static $s_controller;
	public static $s_method;
	
	function __construct(){
		$this->_default = site_config();
		self::view_controller();
	}

	public static function get_instance(){
		if (!isset(self::$s_instance)){
			self::$s_instance = new self();
		}
		return self::$s_instance;
	}
	
	private function _fetch_url(){
		$url = '';
		$controller_arr = array();
		$url_arr = explode('.', str_replace(SITEPATH, '/', $_SERVER['REQUEST_URI']));

		$uri = ($url_arr[0] == '/') ? '/' : $url_arr[0];
		if($uri == '/'){				
			$controller_arr['name'] = $this->_default['default_controller'];
			$controller_arr['url'] = BASEPATH.'controller/'.$this->_default['default_controller'].EXT;
			$controller_arr['method'] = $this->_default['default_function'];
		}else{			
			$uri_arr = explode($this->_default['url'], $uri);			
			foreach($uri_arr as $key => $val){	
				if(empty($val))continue;		 
				$file = $url.$val;		
				$url .= $val.$this->_default['url'];
				if(file_exists(BASEPATH.'controller/'.$file.EXT)){	
					$controller_arr['name'] = $val;
					$controller_arr['url'] = BASEPATH.'controller/'.$file.EXT;
					$fun_url = substr($uri, strlen($file)+2);	
					$fun_arr = explode($this->_default['url'], $fun_url);		
					$controller_arr['method'] = empty($fun_arr[0]) ? 'index' : $fun_arr[0];
					$controller_arr['fun_arr'] = array_splice($fun_arr, 1); 	
					$controller_arr['dir'] = $file.'/'.$controller_arr['method'];
					break;
				}		
			}
		}
		return $controller_arr;
	}
	
	private function view_controller(){
		$controller_arr = self::_fetch_url();
		if(empty($controller_arr)){
			header("HTTP/1.0 404 Not Found");
			require BASEPATH.'view/404.php';exit;
			return;
		}		
		require $controller_arr['url'];
		if(method_exists($controller_arr['name'], $controller_arr['method'])){
			self::$s_directory = empty($controller_arr['dir'])?'':substr($controller_arr['dir'], 0, -strlen('/'.$controller_arr['name'].'/'.$controller_arr['method']));
			self::$s_controller = $controller_arr['name'];
			self::$s_method = $controller_arr['method'];
			Base::insert_func_array($controller_arr);
		}else{
			header("HTTP/1.0 404 Not Found");
			require BASEPATH.'view/404.php';exit;
			return;
		}
	}
}
?>