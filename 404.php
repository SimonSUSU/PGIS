<?php
error_reporting(0);
header('HTTP/1.1 200 OK'); //先重置为200，避免第一次加载图片生成时浏览器解析不出404的图片。 simonsu 2015/4/28

$url = $_SERVER['REQUEST_URI'];
$ext_temp = explode('.', $url);
$ext = $ext_temp[1];
$url_temp = substr($ext_temp[0], 1);	//去掉最前面的/
$url_arr = explode('_', $url_temp);

if (empty($url_arr)) {
	header("HTTP/1.0 404 Not Found");
	//Header("Location:/design_by_simonsu_404_0");
	exit;	//转向出错提示页
}

$filename = $url_arr[0] . '.' . $ext;
$filename = str_replace('thumb/', 'upload/', $filename);	//转换获得原图地址 2013/1/19

$ext_arr = array('jpg', 'gif', 'png', 'jpeg', 'JPG');
$is_upload = strstr($filename, 'upload/');
if (!in_array(strtolower($ext), $ext_arr) || empty($is_upload)) { //不是图片，或不是上传目录的，不往后执行
	header("HTTP/1.0 404 Not Found");
	//Header("Location:/design_by_simonsu_404_1");
	exit; //转向出错提示页
}

//$filename = 'upload/32/526577692f880fd85.jpeg';
//$filenamePath =str_replace('/upload/','upload/',$filename);	//因为is_file的问题，所以去掉了/
if (!is_file($filename)) {
	header("HTTP/1.0 404 Not Found");
	//Header("Location:/design_by_simonsu_404_2");
	exit;	//转向出错提示页
}

$width = substr($url_arr[1], 1);
$height = substr($url_arr[2], 1);
$thumb = $url_arr[0] . '_w' . $width . '_h' . $height . '.' . $ext;	//小图地址



$dir = dirname($thumb); // 算出小图目录 simonsu 2013/1/19
if (!is_dir($dir)) {
	create_folders($dir);
}

$img_width = $width; //生成图片的宽
$imt_height = $height; //生成图片的高
$size_arr = array('640|360', '200|200', '800|400', '0|0', '100|100', '120|90', '200|150', '240|320', '320|240', '320|320', '640|360', '640|480', '640|640', '800|450', '960|480');

list($width_orig, $height_orig) = getimagesize($filename); //获取原图的长和宽

if ($img_width != 0 && $imt_height != 0) {
	$size = $width . '|' . $height; //获取缩略图的长和宽
	if (!in_array($size, $size_arr)) {
		header("HTTP/1.0 404 Not Found");
		exit;	//转向出错提示页
	}
} else { //此由SIMONSU 添加 2015/4/13	
	//当宽为0时，高不为0时，按比例自动计算得到宽； simonsu 2015/4/13
	if ($width == 0 && $height != 0) {
		foreach ($size_arr as $k => $v) {
			$size_rs[$k] = explode("|", $v);
			if ($height == $size_rs[$k]['1']) {
				$width = floor($width_orig * $height / $height_orig); //向下舍入
				break;
			}
		}
		if ($width == 0) {
			header("HTTP/1.0 404 Not Found");
			//Header("Location:/design_by_simonsu_404_4");
			exit;	//转向出错提示页
		}
	}


	//当高为0时，宽不为0时，按比例自动计算得到高； simonsu 2015/4/13
	if ($height == 0 && $width != 0) {
		foreach ($size_arr as $k => $v) {
			$size_rs[$k] = explode("|", $v);
			if ($width == $size_rs[$k]['0']) {
				$height = floor($height_orig * $width / $width_orig); //向下舍入
				break;
			}
		}
		if ($height == 0) {
			header("HTTP/1.0 404 Not Found");
			//Header("Location:/design_by_simonsu_404_5");
			exit;	//转向出错提示页
		}
	}

	if ($width == 0 && $height == 0) {
		$width = $width_orig;
		$height = $height_orig;
		if ($width_orig >= 1000) {
			$width = 1000;
			$height = floor($height_orig * $width / $width_orig); //向下舍入
		}
		//else{
		// copy($filename, $thumb);
		// WXImages($filename);
		// if($img_width >=600){
		// 	setWater($thumb, '','中国医学论坛报今日循环', '255,255,255', '9', './msyh.ttc','text');
		// }
		// exit;	
		//}	
	}
	$img_width = $width; //生成图片的宽
	$imt_height = $height; //生成图片的高
}


@header("Content-Type:image/jpeg");

$scale_org = $width_orig / $height_orig;
if ($width_orig / $width > $height_orig / $height) {
	$lessen_width  = $height * $scale_org;
	$lessen_height = $height;
} else {
	$lessen_width  = $width;
	$lessen_height  = $width / $scale_org;
}
$dst_x = ($width  - $lessen_width)  / 2;
$dst_y = ($height - $lessen_height) / 2;
/*
 echo "原图比例".$scale_org."<hr>";
 echo '原图宽'.$width_orig."---原图高".$height_orig."<hr>";
 echo '小图的宽'.$width."---高".$height.'<hr>';
 echo '原图缩小后用的宽'.$lessen_width."---高".$lessen_height.'<hr>';
 */
$image_p = imagecreatetruecolor($width, $height); //生成一副缩略图

$image = WXGetImages($filename); //获取原图
//imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
//imagecopyresampled($image_p, $image, 0, 0, 0, 0, $lessen_width, $lessen_height, $width_orig, $height_orig);
WXImagecopyresampled($image_p, $image, 0, 0, 0, 0, $lessen_width, $lessen_height, $width_orig, $height_orig);
//按比列缩小（缩略图，原图，上标配,下标配,左标配,右标配, ）

if ($img_width >= 600 && strpos($thumb, 'editor')) { //图片宽大于600才加水印
	setWater($thumb, '', '中国医学论坛报今日循环', '255,255,255', '9', './static/water_font.ttc', 'text');
}

/*
 * 原有方法仅支付JPG，弃用
 //imagejpeg($image_p, null, 80);//生成小图
 //imagejpeg($image_p, $thumb, 80);//保存小图
 //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $lessen_width, $lessen_height, $width_orig, $height_orig);
 WXImages($image_p, null, 90);//生成小图
 WXImages($image_p, $thumb);//保存小图
 */


/* 创建目录，支付多级目录 simonsu 2013-01-29 */
function create_folders($dir)
{
	return is_dir($dir) or (create_folders(dirname($dir)) and mkdir($dir, 0777));
}


//////////////////////
/* 图片锐化 */
function GDMakeJpegLookLikeCrap($target)
{
	$image = imagecreatefromjpeg($target);
	$spnMatrix = array(
		array(-1, -1, -1,),
		array(-1, 16, -1,),
		array(-1, -1, -1)
	);
	$divisor = 8;
	$offset = 0;
	imageconvolution($image, $spnMatrix, $divisor, $offset);
	// I like to send headers as late as possible to avoid already sent errors and duplicate header content
	header('Content-type: image/jpeg');
	imagejpeg($image, null, 80);
	imagedestroy($image);
}
//////////////////////////////
//图片操作
//通过判断文件的格式决定image处理器
function WXImages($filename, $path = null, $quality = 80)
{
	$ext = strtolower(substr(strrchr($filename, '.'), 1));
	if ($path != null) {
		$ext = strtolower(substr(strrchr($path, '.'), 1));
	}
	if ($path == null) {
		switch ($ext) {
			case 'jpg':
				header('Content-type: image/jpeg');
				$im = @imagecreatefromjpeg($filename);
				imagejpeg($im);
				break;

			case 'gif':
				header('Content-type: image/gif');
				$im = @imagecreatefromgif($filename);
				imagegif($im);
				break;

			case 'png':
				header('Content-type: image/png');
				$im = @imagecreatefrompng($filename);
				imagepng($im);
				break;

			default:
				header('Content-type: image/jpeg');
				$im = @imagecreatefromjpeg($filename);
				imagejpeg($im);
				break;
		}
	} else {
		switch ($ext) {
			case 'jpg':
				imagejpeg($filename, $path, $quality);
				break;
			case 'gif':
				imagegif($filename, $path);
				break;
			case 'png':
				imagepng($filename, $path);
				break;

			default:
				imagejpeg($filename, $path, $quality);
				break;
				break;
		}
	}
}
// 输出图片到浏览器
function WXImageShow($image, $filename)
{
	$ext = strtolower(substr(strrchr($filename, '.'), 1));
	switch ($ext) {
		case 'jpg':
			header('Content-type: image/jpeg');
			imagejpeg($image);
			break;
		case 'gif':
			header('Content-type: image/gif');
			imagegif($image);
			break;
		case 'png':
			header('Content-type: image/png');
			imagepng($image);
			break;
		default:
			break;
	}
}
//取得图片
function WXGetImages($filename)
{
	ini_set("memory_limit", "512M");
	$type = getImagetype($filename);
	//$ext = strtolower(substr(strrchr($filename, '.'), 1));
	switch ($type) {
		case 'jpg':
			return @imagecreatefromjpeg($filename);
			break;
		case 'gif':
			return @imagecreatefromgif($filename);
			break;
		case 'png':
			return @imagecreatefrompng($filename);
			break;
		default:
			return @imagecreatefromjpeg($filename);
			break;
	}
}

//生成缩略图并保存
//暂时支持：jpg、png、gif
function WXImagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
	global $filename;
	global $thumb;

	$ext = strtolower(substr(strrchr($thumb, '.'), 1));
	switch ($ext) {
		case 'jpg':
			imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
			//WXImages($dst_image, null, 80);//生成小图
			WXImages($dst_image, $thumb, 80); //保存小图
			WXImageShow($dst_image, $thumb); //输出小图
			break;
		case 'gif':
			WXThum($thumb, $filename, $dst_w, $dst_h);
			break;
		case 'png':
			imagesavealpha($src_image, true);
			imagealphablending($dst_image, false);
			imagesavealpha($dst_image, true);
			imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
			//WXImages($dst_image, null, 80);//生成并输出
			WXImages($dst_image, $thumb, 80); //保存小图
			WXImageShow($dst_image, $thumb); //输出小图
			break;
		default:
			imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
			//WXImages($dst_image, null, 80);//生成小图
			WXImages($dst_image, $thumb, 80); //保存小图
			WXImageShow($dst_image, $thumb); //输出小图
			break;
	}
}
/**
 * 等比例缩放
 * @param 源 $res
 * @param 缩放后的最大宽 $width
 * @param 缩放后的最大高 $height
 * @param 目标 $new
 */
function WXThum($newname, $res, $width, $height)
{
	list($s_w, $s_h) = getimagesize($res);
	if ($width && ($s_w < $s_h)) {
		$width = ($height / $s_h) * $s_w;
	} else {
		$height = ($width / $s_w) * $s_h;
	}
	$newfile = imagecreatetruecolor($width, $height);
	$img = imagecreatefromgif($res);

	$otsc = imagecolortransparent($img);
	//如果存在透明色
	if ($otsc >= 0 && $otsc < imagecolorstotal($img)) {
		//查找索引颜色的值
		$tran = imagecolorsforindex($img, $otsc);
		//去除透明色的背景颜色
		$newcolor = imagecolorallocate($newfile, $tran["red"], $tran["green"], $tran["blue"]);
		imagefill($newfile, 0, 0, $newcolor);
		//将新图片的地方指定透明色
		imagecolortransparent($newfile, $newcolor);
	}
	//开始拷贝，透明色的时候用imagecopyresized才没有雪花
	imagecopyresized($newfile, $img, 0, 0, 0, 0, $width, $height, $s_w, $s_h);
	WXImages($newfile, null, 80); //生成并输出
	WXImages($newfile, $newname); //保存小图
	WXImageShow($newfile, $newname); //输出小图
	//WXImageShow($dst_image, $thumb);//输出小图
	//imagegif($newfile,$newname);
	//imagedestroy($img);
	//imagedestroy($newfile);
}

/*
$imgSrc：目标图片，可带相对目录地址，
$markImg：水印图片，可带相对目录地址，支持PNG和GIF两种格式，如水印图片在执行文件mark目录下，可写成：mark/mark.gif
$markText：给图片添加的水印文字
$TextColor：水印文字的字体颜色
$markPos：图片水印添加的位置，取值范围：0~9
0：随机位置，在1~8之间随机选取一个位置
1：顶部居左 2：顶部居中 3：顶部居右 4：左边居中
5：图片中心 6：右边居中 7：底部居左 8：底部居中 9：底部居右
$fontType：具体的字体库，可带相对目录地址
$markType：图片添加水印的方式，img代表以图片方式，text代表以文字方式添加水印
*/
function setWater($imgSrc = '', $markImg = '', $markText = '', $TextColor = '', $markPos = '', $fontType = '', $markType = 'img')
{

	$srcInfo = @getimagesize($imgSrc);
	$srcImg_w    = $srcInfo[0];
	$srcImg_h    = $srcInfo[1];

	switch ($srcInfo[2]) {
		case 1:
			$srcim = imagecreatefromgif($imgSrc);
			break;
		case 2:
			$srcim = imagecreatefromjpeg($imgSrc);
			break;
		case 3:
			$srcim = imagecreatefrompng($imgSrc);
			break;
		default:
			die("不支持的图片文件类型");
			exit;
	}

	if (!strcmp($markType, "img")) {
		if (!file_exists($markImg) || empty($markImg)) {
			return;
		}

		$markImgInfo = @getimagesize($markImg);
		$markImg_w    = $markImgInfo[0];
		$markImg_h    = $markImgInfo[1];


		if ($srcImg_w < $markImg_w || $srcImg_h < $markImg_h) {
			return;
		}

		switch ($markImgInfo[2]) {
			case 1:
				$markim = imagecreatefromgif($markImg);
				break;
			case 2:
				$markim = imagecreatefromjpeg($markImg);
				break;
			case 3:
				$markim = imagecreatefrompng($markImg);
				break;
			default:
				die("不支持的水印图片文件类型");
				exit;
		}

		$logow = $markImg_w;
		$logoh = $markImg_h;
	}

	if (!strcmp($markType, 'text')) {
		$fontSize = 20;
		if (!empty($markText)) {
			if (!file_exists($fontType)) {
				return;
			}
		} else {
			return;
		}

		$box = @imagettfbbox($fontSize, 0, $fontType, $markText);
		$logow = max($box[2], $box[4]) - min($box[0], $box[6]);
		$logoh = max($box[1], $box[3]) - min($box[5], $box[7]);
	}
	if ($markPos == 0) {
		$markPos = rand(1, 9);
	}

	switch ($markPos) {
		case 1:
			$x = +5;
			$y = +5;
			break;
		case 2:
			$x = ($srcImg_w - $logow) / 2;
			$y = +5;
			break;
		case 3:
			$x = $srcImg_w - $logow - 5;
			$y = +15;
			break;
		case 4:
			$x = +5;
			$y = ($srcImg_h - $logoh) / 2;
			break;
		case 5:
			$x = ($srcImg_w - $logow) / 2;
			$y = ($srcImg_h - $logoh) / 2;
			break;
		case 6:
			$x = $srcImg_w - $logow - 5;
			$y = ($srcImg_h - $logoh) / 2;
			break;
		case 7:
			$x = +5;
			$y = $srcImg_h - $logoh - 5;
			break;
		case 8:
			$x = ($srcImg_w - $logow) / 2;
			$y = $srcImg_h - $logoh - 5;
			break;
		case 9:
			$x = $srcImg_w - $logow - 15;
			$y = $srcImg_h - $logoh + 5;
			break;
		default:
			die("此位置不支持");
			exit;
	}

	$dst_img = @imagecreatetruecolor($srcImg_w, $srcImg_h);

	imagecopy($dst_img, $srcim, 0, 0, 0, 0, $srcImg_w, $srcImg_h);

	if (!strcmp($markType, "img")) {
		imagecopy($dst_img, $markim, $x, $y, 0, 0, $logow, $logoh);
		imagedestroy($markim);
	}

	if (!strcmp($markType, "text")) {
		$rgb = explode(',', $TextColor);

		$color = @imagecolorallocate($dst_img, $rgb[0], $rgb[1], $rgb[2]);
		imagettftext($dst_img, $fontSize, 0, $x, $y, $color, $fontType, $markText);
	}

	switch ($srcInfo[2]) {
		case 1:
			imagegif($dst_img, $imgSrc);
			break;
		case 2:
			imagejpeg($dst_img, $imgSrc);
			break;
		case 3:
			imagepng($dst_img, $imgSrc);
			break;
		default:
			die("不支持的水印图片文件类型");
			exit;
	}

	imagedestroy($dst_img);
	imagedestroy($srcim);
}

//取得图片类型
function getImagetype($filename)
{
	$file = fopen($filename, 'rb');
	$bin = fread($file, 2); //只读2字节
	fclose($file);
	$strInfo = @unpack('C2chars', $bin);
	$typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
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
