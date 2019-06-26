<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 路径函数 */
function url($url_arr){
	$url = array();
	foreach ($url_arr as $key => $val){
		$url[] = $val;
	}
	return '/'.implode('/', $url).'/';
}

function json($arr){
	header('Content-type:text/json');
	return json_encode($arr);
}

//msgbox(标题，类型，内容，跳转地址，暂停时间秒)
function msgbox($title='',$type='',$content='',$url='',$times=''){
	$title = empty($title) ? 'Message': $title;	
	$type = empty($type) ? '1': $type;	//1提示，2警告，3错误，4返回(history.back)
	$content = empty($content) ? '': $content;
	$url = empty($url) ? '/': $url;
	$times = empty($times) ? '1': $times;

	header('Content-type: text/html; charset=utf-8');
	switch ($type) {
		case '6':
			echo '<!DOCTYPE html>';
			echo '<meta charset="UTF-8">';
			echo '<script>alert("'.$content.'"); </script>'; exit;
		break;

		case '5':
			echo '<!DOCTYPE html>';
			echo '<meta charset="UTF-8">';
			echo '<script>alert("'.$content.'"); window.location.href ="'.$url.'";</script>';exit;
		break;

		case '4':
		echo '<!DOCTYPE html>';
		echo '<meta charset="UTF-8">';
		echo '<script>alert("'.$content.'"); javascript:history.back();</script>';exit;
		break;

		case '3':
			echo '<!DOCTYPE html>';
			echo '<meta charset="UTF-8">';
			echo '<script>window.history.back(); location.reload(); alert("'.$content.'"); </script>'; exit;
		break;

		case '2':
			echo '<!DOCTYPE html>';
			echo '<meta charset="UTF-8">';
			echo '<script type="text/javascript" src="'.JS_PATH.'jquery.js" charset="utf-8"></script>';
			echo '<script type="text/javascript" src="'.JS_PATH.'layer/layer.js" charset="utf-8"></script>';
            echo '<script>alert("'.$content.'"); parent.location.reload(); var index = parent.layer.getFrameIndex(window.name); parent.layer.close(index);</script>'; exit;
		break;
		
		default:
			echo '<!DOCTYPE html>';
			echo '<meta charset="UTF-8">';
			echo '<script>window.location.href ="'.$url.'";</script>';exit;
		break;
	}
	exit;
}


/* 获取IP */
function ip(){
	if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){	//采用LBS负载，所以需用这个来获取真实IP SIMONSU 2013/6/29
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(!empty($_SERVER["HTTP_CLIENT_IP"])){
		return $_SERVER["HTTP_CLIENT_IP"];
	}elseif(!empty($_SERVER["HTTP_JWTIP"])){
		return $_SERVER["HTTP_JWTIP"];
	}elseif(!empty($_SERVER["REMOTE_ADDR"])){
		return $_SERVER["REMOTE_ADDR"];
	}else{
		return '0.0.0.0';
	}
}


/* 自定义的IP转数字 */
function myip2long($ip=''){
	$iparr = explode('.',$ip);
    return (intval($iparr[0]<<24))|(intval($iparr[1])<<16)|(intval($iparr[2])<<8)| (intval($iparr[3]));
}



function create_password($length = 8) {
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*';
    $password = '';
    for ( $i = 0; $i < $length; $i++ ){
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
}


/*
* 加密解密方法
*  $tmp1 = diy_encode('tex','key'); //加密
*  $tmp2 = diy_encode($tmp1,'key','decode'); //解密
*/
function diy_encode($tex='', $key='', $type="encode"){//encode加密， decode 解密
    $chrArr=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                  'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                  '0','1','2','3','4','5','6','7','8','9');
    if($type=="decode"){
        if(strlen($tex)<14)return false;
        $verity_str=substr($tex, 0,8);
        $tex=substr($tex, 8);
        if($verity_str!=substr(md5($tex),0,8)){//完整性验证失败            
            return false;
        }    
    }
    $key_b=$type=="decode"?substr($tex,0,6):$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
    $rand_key=$key_b.$key;
    $rand_key=md5($rand_key);
    $tex=$type=="decode"?base64_decode(substr($tex, 6)):$tex;
    $texlen=strlen($tex);
    $reslutstr="";
    for($i=0;$i<$texlen;$i++){
        $reslutstr.=$tex{$i}^$rand_key{$i%32};
    }
    if($type!="decode"){
        $reslutstr=trim($key_b.base64_encode($reslutstr),"==");
        $reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
    }
    return $reslutstr;
}

/* 截取并生成纯文本字符串 */
function cutstr_html($string='', $sublen=''){
	$string = strip_tags($string);
	$string = preg_replace ('/\n/is', '', $string);
	$string = preg_replace ('/ |　/is', '', $string);
	$string = preg_replace ('/&nbsp;/is', '', $string);
	$string = str_replace(array("/r/n", "/r", "/n"), "", $string); 
	$string = str_replace(array("\r\n", "\r", "\n"), "", $string);   //过滤换行
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
	if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
	else $string = join('', array_slice($t_string[0], 0, $sublen));
	return $string;
}

function delfile($file=''){
	if (!unlink($file)){
		return 'Error';
	}else{
		return 'Success';
	}
}

/* IP转实际地址 */
function ip2addr($ip=''){	
	$url ='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
	$urlkey =md5($url);
	require_once("lib/classes/WEBcache.php");
	$mch = new WEBcache();
	$urlData = $mch->getCache($urlkey);
	if(empty($urlData)){
		@$rs=file_get_contents($url);
		$mch->setCache($urlkey,$rs);
	}else{
		$rs=$urlData;
	}
	$rs =json_decode($rs);
	foreach ($rs->data as $key => $value) {
	    $arr[$key] = $value;
    }
	return $arr;
}

//创建路径2014/12/17
 function mkDirs($path='', $mode=0777) { 
        if (!file_exists($path)) { 
            mkDirs(dirname($path), $mode); 
            if(mkdir($path, $mode)) {
                return true; 
            } else { 
                return false;
           }
       } else { 
           return true;
       }
   }
   
 //写入文本文件2014/12/17  
function createfile($filepath='',$filename='',$content='', $mode='w'){
    if(!is_dir($filepath)){
       mkDirs($filepath);
    }
    $file=$filepath.'/'.$filename;
    $fp = @fopen($file,$mode);
    @fwrite($fp,"$content\r\n");
    @fclose($fp);
}


//将数组转换成请求字符串  k1=v1&k2=v2
function arrayToRequestString($para='') {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".urlencode($val)."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}



/*
  生成列表结构
  @param array 原始数组
  @param int 需要查询的id
  @param string 方向 查询id的子集还是父级
*/
function genTree(&$items, $id=0, $direction='children'){
	static $tree; //格式化的树
	static $lv=0;
	if(empty($items)){
		return false;
	}
	if(empty($id)&&$direction == 'parent'){
		return false;
	}
	if($lv == 0){
		$tree = array();
		foreach($items as $k=>$v){
	    	if($v['id'] == $id){
	    		$tree[$id] = $v;
	    	}
	    }
	    if(!empty($id)&&empty($tree)){
	    	return false;
	    }
	}

	if($direction == 'children'){
	    $lv++;
	    foreach($items as $k=>$v){
	        if($v['pid']==$id){
	            $tree[$v['id']]=$v;
	            genTree($items,$v['id']);
	        }
	    }
	    $lv--;
	   	return $tree;

	}elseif($direction == 'parent'){
	    $lv++;
	    foreach($items as $k=>$v){
	    	if($v['id'] == $id){
	    		$pid = $v['pid'];
	    	}
	    }
	    foreach($items as $k=>$v){
	        if($v['id']==$pid){
	            $tree[$pid]=$v;
	            genTree($items,$pid,'parent');
	        }
	    }
	    $lv--;
	   	return $tree;
	}else{
		return '';
	}
}

/*
  生成列表结构
  @param array 原始数组
  @param int 需要查询的id
  @param string 方向 查询id的子集还是父级

  return 键为顺序递增的数组
*/
function genTree3(&$items, $id=0, $direction='children'){
	static $tree; //格式化的树
	static $lv=0;
	if(empty($items)){
		return false;
	}
	if(empty($id)&&$direction == 'parent'){
		return false;
	}
	if($lv == 0){
		$tree = array();
		foreach($items as $k=>$v){
	    	if($v['id'] == $id){
	    		$v['level'] = $lv;
	    		$tree[] = $v;
	    	}
	    }
	    if(!empty($id)&&empty($tree)){
	    	return false;
	    }
	}

	if($direction == 'children'){
	    $lv++;
	    foreach($items as $k=>$v){
	        if($v['pid']==$id){
	            $v['level']=$lv;
	            $tree[]=$v;
	            genTree3($items,$v['id']);
	        }
	    }
	    $lv--;
	   	return $tree;

	}elseif($direction == 'parent'){
	    $lv++;
	    foreach($items as $k=>$v){
	    	if($v['id'] == $id){
	    		$pid = $v['pid'];
	    	}
	    }
	    foreach($items as $k=>$v){
	        if($v['id']==$pid){
	            $v['level']=$lv;
	            $tree[]=$v;
	            genTree3($items,$pid,'parent');
	        }
	    }
	    $lv--;
	   	return $tree;
	}else{
		return '';
	}
}


/*
 * Name : 目录树数组
 */
function genTree2($items, $id='id', $pid='pid', $son = 'children'){
	$tree = array(); //格式化的树
	$tmpMap = array();  //临时扁平数据

	foreach ($items as $item) {
		$tmpMap[$item[$id]] = $item;
	}

	foreach ($items as $item) {
		if (isset($tmpMap[$item[$pid]])) {
			$tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
		} else {
			$tree[] = &$tmpMap[$item[$id]];
		}
	}
	unset($tmpMap);
	return $tree;
}


//判断是否为weburl格式
function webUrlCheck($url=''){
	if(!preg_match('/https?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
		return false;
	}else{
		return true;
	}
}


//给出月份，算出开始日期和结束日期
function get_month_date($date=''){
    $timestamp=strtotime($date);
    $firstday = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.date('m',$timestamp).'-01'));
    $lastday = date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    return array($firstday, $lastday);
}

//给出月份，算出开始日期和结束日期
function get_month_time($date=''){
    $timestamp=strtotime($date);
    $firstday = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.date('m',$timestamp).'-01'));
    $lastday = date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    return array($firstday.' 00:00:00', $lastday.' 23:59:59');
}

//判断字符串是否是手机号码
function is_phone( $phone='') {
    $temp = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
    if ( preg_match($temp, $phone) ) {
        return true;
    } else {
        return false;
    }
}

//隐藏手机号中间四位
function hidden_phone($phone=''){
	if( empty($phone) || !is_numeric($phone)){
		return false;
	}
	return substr_replace($phone, '****', 3, 4);
}

//隐藏身份证号码的第
function hidden_idcard($phone=''){
	if( empty($phone) || !is_numeric($phone)){
		return false;
	}
	return substr_replace($phone, '*****', 10, 5);
}


//判断是否是正确的邮箱格式;
function is_email($email=''){
	$temp = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
	if(preg_match($temp,$email)){
		return true;
	}else{
		return false;
	}
}

//判断是否是网址
function is_url($url=''){
	$preg = "/^http(s)?:\\/\\/.+/";
	if(preg_match($preg,$url)){
		return true;
	}else{
		return false;
	}
}



//对象转数组
function object_to_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
    } 
    if(is_array($array)) {
        foreach($array as $key=>$value) {  
            $array[$key] = object_to_array($value);  
        }  
    }  
    return $array;  
}

// 过滤掉emoji表情
function filterEmoji($str){
	$str = preg_replace_callback('/./u',function (array $match) {
		return strlen($match[0]) >= 4 ? '' : $match[0];
	},
	$str);
	return $str;
}

function create_folders($dir){
	return is_dir($dir) or (create_folders(dirname($dir)) and mkdir($dir, 0777));
}

//转换文件大小
function transform_file_size($size){
    if($size < 1024){
        $trans_size=round($size,2).'B';
    }elseif ($size/1024 < 1024){
        $trans_size=round($size/1024,2).'KB';
    }elseif ($size/1024/1024 < 1024){
        $trans_size=round($size/1024/1024,2).'MB';
    }elseif ($size/1024/1024/1024 < 1024){
        $trans_size=round($size/1024/1024/1024,2).'G';
    }else{
        $trans_size=round($size/1024/1024/1024,2).'G';
    }
    return $trans_size;
}

//扩展名匹配图标
function ext_to_icon($ext=''){
	$ext_arr = array('3gp','7z','ai','avi','bmp','cab','cd','cdr','dll','dmg','doc','docx','dotx','eps','et','fla','flv','folder','gho','gif','html','ico','iso','jpeg','jpg','midi','mkv','mov','mp3','mp4','none','pdf','png','potx','pps','ppsx','ppt','pptx','psd','ra','rar','rm','rmvb','svg','swf','tar','tif','tiff','txt','vqf','wav','wma','wps','xls','xlsb','xlsm','xlsx','xltx','zip');
	if(in_array(strtolower($ext), $ext_arr)){
		return IMG_PATH.'icon/'.strtolower($ext).'.png';
	}else{
		return IMG_PATH.'icon/none.png';
	}
}




/*
* 函数说明：验证身份证是否真实
* 注：加权因子和校验码串为互联网统计  尾数自己测试11次 任意身份证都可以通过
* 传递参数：
* $number身份证号码
* 返回参数：
* true验证通过
* false验证失败

第二代残疾人证件号为20位数字，前18为身份证号，后2为编号规则如下：
一、残疾类别代码：
视力残疾：1，
听力残疾：2，
言语残疾：3，
肢体残疾：4，
智力残疾：5，
精神残疾：6，
多重残疾：存在2项或2项以上残疾，编号：7

二、残疾等级代码
一级: 1
二级: 2
三级: 3
四级: 4
*/
function checkId($number){
	if(strlen($number)<18){
		return false;
	}
	$number = substr($number,0,18);	//只取前18位
	$sigma = '';	 
	$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);	//加权因子	
	$ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');	//校验码串 

	for ($i = 0;$i < 17;$i++) {//按顺序循环处理前17位
	    $b = (int) $number{$i}; //提取前17位的其中一位，并将变量类型转为实数 
	    $w = $wi[$i]; //提取相应的加权因子 	    
	    $sigma += $b * $w;	//把从身份证号码中提取的一位数字和加权因子相乘，并累加 得到身份证前17位的乘机的和 
	}
	//echo $sigma;die;	
	$snumber = $sigma % 11; //计算序号  用得到的乘机模11 取余数	
	$check_number = $ai[$snumber];	//按照序号从校验码串中提取相应的余数来验证最后一位。 
	if ($number{17} == $check_number) {
	    return true;
	} else {
	    return false;
	}
}


/*
 *  根据身份证号码获取性别
 *  author:xiaochuan
 *  @param string $idcard    身份证号码
 *  @return int $sex 性别 1男 2女 0未知
 */
function idcard_to_sex($idcard) {
    if(empty($idcard)) return null; 
    $sexint = (int) substr($idcard, 16, 1);
    return $sexint % 2 === 0 ? 2 : 1;	//1男，2女，0未知
}
/*
 *  根据身份证号码获取生日
 *  author:xiaochuan
 *  @param string $idcard    身份证号码
 *  @return $birthday
 */
function idcard_to_birth($idcard) {
    if(empty($idcard)) return null; 
    $bir = substr($idcard, 6, 8);
    $year = (int) substr($bir, 0, 4);
    $month = (int) substr($bir, 4, 2);
    $day = (int) substr($bir, 6, 2);
    return $year . "-" . $month . "-" . $day;
}


//根据出生日期计算年龄
function get_age($birth){
	$byear = date('Y',strtotime($birth));
	$bmonth = date('m',strtotime($birth));
	$bday = date('d',strtotime($birth));

	$tyear = date('Y');
	$tmonth = date('m');
	$tday = date('d');

	$age=$tyear-$byear;
	if($bmonth > $tmonth || $bmonth==$tmonth && $bday > $tday ){
		$age--;
	}
	return $age;
}



//二维数组求差集
function diy_array_diff_assoc2_deep($array1, $array2,$ret=array() ){ 
    foreach ($array1 as $k => $v) {
        if (!isset($array2[$k])){
            $ret[$k] = $v;
        }elseif( is_array($v) && is_array($array2[$k])){
            $ret[$k] = diy_array_diff_assoc2_deep($v, $array2[$k], $ret);  
        }elseif($v !=$array2[$k]){
            $ret[$k] = $v;
        }else{
            unset($array1[$k]);
        }
    } 
    $ret = array_filter($ret); 	//去数组中的空元素
    return $ret; 
}



/**
 * 计算出两个日期之间的月份
 * @param  [type] $start_date [开始日期，如2014-03]
 * @param  [type] $end_date   [结束日期，如2015-12]
 * @param  string $explode    [年份和月份之间分隔符，此例为 - ]
 * @param  boolean $addOne    [算取完之后最后是否加一月，用于算取时间戳用]
 * @return [type]			[返回是两个月份之间所有月份字符串]
 */
function dateMonths($start_date='', $end_date='', $explode='-', $addOne=false){
	//判断两个时间是不是需要调换顺序
	$start_int = strtotime($start_date);
	$end_int = strtotime($end_date);
	if($start_int > $end_int){
		$tmp = $start_date;
		$start_date = $end_date;
		$end_date = $tmp;
	}

	//结束时间月份+1，如果是13则为新年的一月份
	$start_arr = explode($explode,$start_date);
	$start_year = intval($start_arr[0]);
	$start_month = intval($start_arr[1]);

	$end_arr = explode($explode,$end_date);
	$end_year = intval($end_arr[0]);
	$end_month = intval($end_arr[1]);

	$data = array();
	$data[] = $start_date;

	$tmp_month = $start_month;
	$tmp_year = $start_year;
 	
 	//如果起止不相等，一直循环
	while (!(($tmp_month == $end_month) && ($tmp_year == $end_year))) {
		$tmp_month ++;
		//超过十二月份，到新年的一月份
		if($tmp_month > 12){
			$tmp_month = 1;
			$tmp_year++;
		}
		$data[] = $tmp_year.$explode.str_pad($tmp_month,2,'0',STR_PAD_LEFT);
	}

	if($addOne == true){
		$tmp_month ++;
		//超过十二月份，到新年的一月份
		if($tmp_month > 12){
			$tmp_month = 1;
			$tmp_year++;
		}
		$data[] = $tmp_year.$explode.str_pad($tmp_month,2,'0',STR_PAD_LEFT);
	}
	return $data;
}


//判断是否是手机浏览器
function isMobile(){  
    $user_agent = $_SERVER['HTTP_USER_AGENT'];  
    $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte","MicroMessenger");  
    $is_mobile = false;  
    foreach ($mobile_agents as $device) {//这里把值遍历一遍，用于查找是否有上述字符串出现过  
       if (stristr($user_agent, $device)) { //stristr 查找访客端信息是否在上述数组中，不存在即为PC端。  
            $is_mobile = true;  
            break;  
        }  
    }  
    return $is_mobile;  
}
?>