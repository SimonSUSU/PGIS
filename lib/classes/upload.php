<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload{

	private $_pic_type = array(
		'image/jpg',
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/x-png'
	);

	private $_type = array(
		'image/pjpeg',
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/x-png',
		'image/bmp',
		'application/octet-stream',
		'application/vnd.android.package-archive'
	);

	private $_size = 204800;
	private $_dir;
	private $_name;
	function __construct()
	{
	}
	public function upload_file($file_arr, $diy_dir='other',$limit_type='',$name=''){
		if(!empty($name)){
			$this->_name =  $name.'_'.uniqid(rand(100,999)).rand(1,9);
		}else{
			$this->_name =  uniqid(rand(100,999)).rand(1,9);	
		}
		
		$this->_folder = date('Ym').'/'.date('d');
		//$this->_dir = 'upload/'.$diy_dir.'/'.rand(1,99).'/';
		$this->_dir = 'upload/'.$diy_dir.'/'.$this->_folder.'/';

		if( empty($file_arr['tmp_name']) ){
			return array('code' =>'Error', 'codeNo'=>4, 'msg' => '文件不存在');
		}

		$ext =  substr(strrchr($file_arr['name'], '.'), 1);
		$type = $this->getImagetype($file_arr['tmp_name']);
		if(in_array($type, array('jpg','png','gif','bmp'))){//是图片时
			if($ext != $type){
				return array('code' =>'Error', 'codeNo'=>5, 'msg' => '当前上传的文件为'.$type.'文件，但扩展名为'.$ext.'，请更正后再上传！');
			}
		}		

		switch ($limit_type) {
			case 'pic'://限制类型只为图片
				if(!is_uploaded_file($file_arr['tmp_name']) || !in_array($file_arr['type'], $this->_pic_type)){
					return array('code' =>'Error', 'codeNo'=>1, 'msg' =>'只允许jpg，png，gif文件');
				}
			break;

			case 'video'://限制类型只为视频
			# code...
			break;

			case 'xls'://限制类型只为XLS
			# code...
			break;
			
			default:
				if(!is_uploaded_file($file_arr['tmp_name']) || !in_array($file_arr['type'], $this->_pic_type)){
					return array('code' =>'Error', 'codeNo'=>1, 'msg' =>'只允许jpg，png，gif文件');
				}
			break;
		}
		
		// if(!is_uploaded_file($file_arr['tmp_name']) || !in_array($file_arr['type'], $this->_type)){
		// 	return array('code' =>'Error', 'codeNo'=>1, 'msg' => $file_arr['type'].'文件类型不对');
		// }

		if($file_arr['size'] > ($this->_size * 1024)){
			return array('code' =>'Error', 'codeNo'=>2, 'msg' => '文件超出限制的大小');
		}
		return self::_move_file($file_arr['tmp_name'], $ext);
	
	}


	//取得图片类型
	public function getImagetype($filename){
		$file = fopen($filename, 'rb');
		$bin = fread($file, 2); //只读2字节
		fclose($file);
		$strInfo = @unpack('C2chars', $bin);
		$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
		// dd($typeCode);
		$fileType = '';
		switch ($typeCode) {
			case 255216:
				$fileType = 'jpg';
			break;
			case 7173:
				$fileType = 'gif';
			break;
			case 6677:
				$fileType = 'bmp';
			break;
			case 13780:
				$fileType = 'png';
			break;
			default:
			$fileType = '只能上传图片类型格式';
		}
		return $fileType;
	}

	
	private function _move_file($file, $ext){
		$url = $this->_dir.$this->_name.'.'.$ext;
		if(!is_dir($this->_dir)){
			mkDirs($this->_dir, 0777);
		}
		if(!move_uploaded_file($file, $url)){
			return array('code' =>'Error', 'codeNo'=>3, 'msg' => '文件移动失败');
		}else{
			return $url;
		}
		
	}
}
?>