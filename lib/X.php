<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//$cXXc=chr(83).chr(45).chr(80).chr(111).chr(119).chr(101).chr(114).chr(101).chr(100).chr(45).chr(66).chr(121).chr(58).chr(119).chr(119).chr(119).chr(46).chr(119).chr(122).chr(112).chr(111).chr(100).chr(46).chr(99).chr(111).chr(109);
//header($cXXc);
date_default_timezone_set('Asia/Shanghai');
define('FILE_PATH', '/static/files/');
define('IMG_PATH', '/static/images/');
define('JS_PATH', '/static/scripts/');
define('CSS_PATH', '/static/styles/');
define('SYS_VERSION','pgis_2019-03-29'.time() );
require LIB.'config/config'.EXT;
require LIB.'config/common'.EXT;
require LIB.'config/Base'.EXT;
require LIB.'config/Db'.EXT;
require LIB.'config/Db_pdo'.EXT;
require LIB.'config/Controllers'.EXT;
require LIB.'config/Router'.EXT;
Router::get_instance();
?>