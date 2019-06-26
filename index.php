<?
//error_reporting(0);//屏蔽警告错误
//error_reporting(E_ALL);
define('EXT','.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('SITEPATH', str_replace(SELF, '', $_SERVER["PHP_SELF"]));
define('BASEPATH', 'system/');
define('LIB', 'lib/');
require_once LIB.'X'.EXT;
?>