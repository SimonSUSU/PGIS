<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('DEBUG','true');  //是否打开调试模式，打开后，整个系统不进行权限处理
//define('APPID','8204246842787284');  //请求本系统API时的APPID，若修改此值，所有客户端的配置需要同时修改
//define('APPKEY','5VVE435Z8edcuZ8a75F4gHoXqh03NvIGQykl');  //请求本系统API时的APPKEY，若修改此值，所有客户端的配置需要同时修改
//define('FILE_URL','http://www.pgis.com');  //文件下载地址，前面有http,后面没有/ (附件的URL，主要作用是为了加速、缓存、避免COOKIE污染等)



function db_config(){
	return array(
		'hi' => array(
	    'host' => '192.168.102.196',
        'username' => 'pgis',
        'password' => '4hgf^$g',
		'db_name' => 'pgis',
		'db_driver' => 'mysql',
		'db_port' => '3306',
		'db_prefix' => 'wz_',
		'charset' => 'utf8mb4')
	);
}

function site_config(){
	return array(
		'web_size' =>'http://www.pgis.com', //前面有http,后面没有/  (网站的URL)
		'file_url' =>'http://www.pgis.com', //前面有http,后面没有/ (附件的URL，主要作用是为了加速、缓存、避免COOKIE污染等)
		'url_key' => '(@cvvb$%hbc56%$fg%__www.pgis.com__JK4Ufg^&!#$!$3%^F$#n%h&2^', //用于URL传递的密钥，可修改；修改后登录身份失效，需重新登录生成才有效
		'suffix' => '.html',
		'default_controller' => 'home',
		'default_function' => 'index',
		'language' => 'cn',
		'url' => '/',
	);
}

// memcache缓存配置
function memcache_config(){
	return array(
		'0' => array('host' => '192.168.102.196', 'port' => 11211)
	);
}
?>