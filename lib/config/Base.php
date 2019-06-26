<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
abstract class Base{

protected $db;
protected $_page = 20;  //默认分页数


public $villager_label_arr = array(
    1=>'未脱贫',
    2=>'已脱贫',
    3=>'返贫',
);

public $villager_type_arr = array(
    2=>'贫困户',
    1=>'农户',
);

public $householder_arr = array(//是否户主
    1=>'否',
    2=>'是',
);

public $user_attribute_arr = array( //村干部、乡镇干部、电商办工作人员、物价局工作人员、财政局工作人员
    1=>'否',
    2=>'是',
);

public $sex_arr = array(
    9 => '未知',
    1 => '男',
    2 => '女',
);

public $status_arr = array(
    1 => '启用',
    2 => '禁用',
    //'3' => '删除',
);

public $hot_modules_arr = array(//广告的信息模块标识
    'article'=>'资讯/article',
);

public $points_type_arr = array(//积分类型
    1=>'问卷',
    2=>'批量导入',
    3=>'手工录入',
    9=>'兑换',
);


public $log_status_arr = array(//日志状态
    1 => '成功',
    2 => '失败',
);


public $goods_status_arr = array(
    1 => '未提交',
    2 => '审核中',
    3 => '已上架',
    4 => '已下架',
);


public $goods_verify_arr = array(
    1 => '待审核',
    2 => '已审核',
    3 => '审核未通过'
);


public $order_status_arr = array(//兑换单状态
    1=>'待兑换',
    2=>'已兑换',
    9=>'已取消',
);

public $order_balance_status_arr = array(//兑换单生成账单状态
    1=>'未生成账单',
    2=>'已生成账单',
);

public $balance_status_arr = array(//账单状态
    9=>'待申请',
    1=>'待审批',
    2=>'审批通过',
    3 => '审核未通过',
);


public $apply_status_arr = array(//各类申请、审批的状态
    1 => '待审批',
    2 => '审批通过',
    3 => '审核未通过',
    4 => '已失效',
);

public $workflow_module_arr = array(//各类申请的状态
    'goods' => '商品采购',
    'assess' => '问卷评分',
    'town_assess' => '乡镇出题',
    'balance' => '兑换结算',    
);

public $workflow_user_type_arr = array(//审批人类型
    1 => '村干部',
    2 => '乡镇干部',
    3 => '物价局',
    4 => '财政局',
    5 => '电商办',
);

public $purview_user_type_arr = array(//权限人员的身份角色，可用于定时器中按角色处理事宜
    10 => '打分员',
    11 => '评分提交员',

    2 => '乡镇干部',
    3 => '物价局',
    4 => '财政局',
    5 => '电商办',
    9 => '超市工作人员',
);


public $assess_category_arr = array( //评分表类型
    2 => '贫困户', //建档立卡贫困户
    1 => '农户',
);

public $assess_type_arr = array( //评分项、评分类型
    1 => '政策知晓类',
    2 => '日常管理类',
    3 => '入户类',//原来未合并之前的：入户调查类
    //4 => '入户摸底类',
    5 => '加分类',
    6 => '一票否决类',
);

public $assess_status_arr = array(//评分主卷状态
    1 => '草稿中',
    2 => '已发布',
    3 => '已停用',
);

public $user_assess_status_arr = array(//用户作答主状态
    1 => '未作答',
    2 => '待审批',
    3 => '审批通过',
    4 => '审批不通过',
    8 => '作答中',
    9 => '已作答',
);

public $user_assess_type_status_arr = array(//用户作答类别状态
    1 => '未作答',
    2 => '已作答',
    8 => '作答中',
);

public $user_assess_claer_arr = array(//is_claer 一票否决：1是，2否
    1 => '是',
    2 => '否',
);

public $townassess_status_arr = array(//乡镇自定义评分配置主卷状态
    1 => '配置中',
    2 => '审批通过', //已发布
    3 => '审批拒绝',
    9 => '待审批',
);


public $ask_assess_arr = array(//问答状态
    1 => '未回复',
    2 => '已回复',
);


public $login_type_arr = array(//登录方式
    1 => '短信登录',
    2 => '扫码登录',
    3 => '公众号登录',
    0 => '未知',
);


public $wxmsglog_status_arr = array(//日志状态
    1 => '失败',
    2 => '成功',
    40001 => '获取access_token时AppSecret错误，或者access_token无效。请开发者认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口',
    40002 => '不合法的凭证类型',
    40003 => '不合法的OpenID，请开发者确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID',
    40004 => '不合法的媒体文件类型',
    40005 => '不合法的文件类型',
    40006 => '不合法的文件大小',
    40007 => '不合法的媒体文件id',
    40008 => '不合法的消息类型',
    40009 => '不合法的图片文件大小',
    40010 => '不合法的语音文件大小',
    40011 => '不合法的视频文件大小',
    40012 => '不合法的缩略图文件大小',
    40013 => '不合法的AppID，请开发者检查AppID的正确性，避免异常字符，注意大小写',
    40014 => '不合法的access_token，请开发者认真比对access_token的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口',
    40015 => '不合法的菜单类型',
    40016 => '不合法的按钮个数',
    40017 => '不合法的按钮个数',
    40018 => '不合法的按钮名字长度',
    40019 => '不合法的按钮KEY长度',
    40020 => '不合法的按钮URL长度',
    40021 => '不合法的菜单版本号',
    40022 => '不合法的子菜单级数',
    40023 => '不合法的子菜单按钮个数',
    40024 => '不合法的子菜单按钮类型',
    40025 => '不合法的子菜单按钮名字长度',
    40026 => '不合法的子菜单按钮KEY长度',
    40027 => '不合法的子菜单按钮URL长度',
    40028 => '不合法的自定义菜单使用用户',
    40029 => '不合法的oauth_code',
    40030 => '不合法的refresh_token',
    40031 => '不合法的openid列表',
    40032 => '不合法的openid列表长度',
    40033 => '不合法的请求字符，不能包含\uxxxx格式的字符',
    40035 => '不合法的参数',
    40038 => '不合法的请求格式',
    40039 => '不合法的URL长度',
    40050 => '不合法的分组id',
    40051 => '分组名字不合法',
    40117 => '分组名字不合法',
    40118 => 'media_id大小不合法',
    40119 => 'button类型错误',
    40120 => 'button类型错误',
    40121 => '不合法的media_id类型',
    40132 => '微信号不合法',
    40137 => '不支持的图片格式',
    41001 => '缺少access_token参数',
    41002 => '缺少appid参数',
    41003 => '缺少refresh_token参数',
    41004 => '缺少secret参数',
    41005 => '缺少多媒体文件数据',
    41006 => '缺少media_id参数',
    41007 => '缺少子菜单数据',
    41008 => '缺少oauth code',
    41009 => '缺少openid',
    42001 => 'access_token超时，请检查access_token的有效期，请参考基础支持-获取access_token中，对access_token的详细机制说明',
    42002 => 'refresh_token超时',
    42003 => 'oauth_code超时',
    42007 => '用户修改微信密码，accesstoken和refreshtoken失效，需要重新授权',
    43001 => '需要GET请求',
    43002 => '需要POST请求',
    43003 => '需要HTTPS请求',
    43004 => '需要接收者关注',
    43005 => '需要好友关系',
    44001 => '多媒体文件为空',
    44002 => 'POST的数据包为空',
    44003 => '图文消息内容为空',
    44004 => '文本消息内容为空',
    45001 => '多媒体文件大小超过限制',
    45002 => '消息内容超过限制',
    45003 => '标题字段超过限制',
    45004 => '描述字段超过限制',
    45005 => '链接字段超过限制',
    45006 => '图片链接字段超过限制',
    45007 => '语音播放时间超过限制',
    45008 => '图文消息超过限制',
    45009 => '接口调用超过限制',
    45010 => '创建菜单个数超过限制',
    45015 => '回复时间超过限制',
    45016 => '系统分组，不允许修改',
    45017 => '分组名字过长',
    45018 => '分组数量超过上限',
    45047 => '客服接口下行条数超过上限',
    46001 => '不存在媒体数据',
    46002 => '不存在的菜单版本',
    46003 => '不存在的菜单数据',
    46004 => '不存在的用户',
    47001 => '解析JSON/XML内容错误',
    48001 => 'api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限',
    48004 => 'api接口被封禁，请登录mp.weixin.qq.com查看详情',
    50001 => '用户未授权该api',
    50002 => '用户受限，可能是违规后接口被封禁',
    61451 => '参数错误(invalid parameter)',
    61452 => '无效客服账号(invalid kf_account)',
    61453 => '客服帐号已存在(kf_account exsited)',
    61454 => '客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid kf_acount length)',
    61455 => '客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)',
    61456 => '客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)',
    61457 => '无效头像文件类型(invalid file type)',
    61450 => '系统错误(system error)',
    61500 => '日期格式错误',
    65301 => '不存在此menuid对应的个性化菜单',
    65302 => '没有相应的用户',
    65303 => '没有默认菜单，不能创建个性化菜单',
    65304 => 'MatchRule信息为空',
    65305 => '个性化菜单数量受限',
    65306 => '不支持个性化菜单的帐号',
    65307 => '个性化菜单信息为空',
    65308 => '包含没有响应类型的button',
    65309 => '个性化菜单开关处于关闭状态',
    65310 => '填写了省份或城市信息，国家信息不能为空',
    65311 => '填写了城市信息，省份信息不能为空',
    65312 => '不合法的国家信息',
    65313 => '不合法的省份信息',
    65314 => '不合法的城市信息',
    65316 => '该公众号的菜单设置了过多的域名外跳（最多跳转到3个域名的链接）',
    65317 => '不合法的URL',
    9001001 => 'POST数据参数不合法',
    9001002 => '远端服务不可用',
    9001003 => 'Ticket不合法',
    9001004 => '获取摇周边用户信息失败',
    9001005 => '获取商户信息失败',
    9001006 => '获取OpenID失败',
    9001007 => '上传文件缺失',
    9001008 => '上传素材的文件类型不合法',
    9001009 => '上传素材的文件尺寸不合法',
    9001010 => '上传失败',
    9001020 => '帐号不合法',
    9001021 => '已有设备激活率低于50%，不能新增设备',
    9001022 => '设备申请数不合法，必须为大于0的数字',
    9001023 => '已存在审核中的设备ID申请',
    9001024 => '一次查询设备ID数量不能超过50',
    9001025 => '设备ID不合法',
    9001026 => '页面ID不合法',
    9001027 => '页面参数不合法',
    9001028 => '一次删除页面ID数量不能超过10',
    9001029 => '页面已应用在设备中，请先解除应用关系再删除',
    9001030 => '一次查询页面ID数量不能超过50',
    9001031 => '时间区间不合法',
    9001032 => '保存设备与页面的绑定关系参数错误',
    9001033 => '门店ID不合法',
    9001034 => '设备备注信息过长',
    9001035 => '设备申请参数不合法',
    9001036 => '查询起始值begin不合法',
);



public $temp_arr = array();

/* 加载模版 */
public function load_view($temp, $data = array()){
    
    if(isMobile()){//是移动端
        $file_path = BASEPATH .'mview/' . $temp . EXT;
        if (!is_file($file_path)) {
            $file_path = BASEPATH .'view/' . $temp . EXT;
        } 
    }else{
        $file_path = BASEPATH .'view/' . $temp . EXT;
    }
    //$file_path = BASEPATH .'view/' . $temp . EXT;
    

    if (!is_file($file_path)) {
        header("HTTP/1.0 404 Not Found");
        echo $file_path.'---不存在'; exit;
    }    
    if (!empty($data)) {
        $this->temp_arr = $data;
        foreach ($this->temp_arr as $key => $val) {
            $$key = $val;
        }
    }
	require $file_path;
}

/* 加载DB */
public function load_db($model){
    static $objects_m = array();
    if (isset($objects_m[$model])) return $objects_m[$model];
    return self::__autoload($model, 'config/');
}

/* 加载 classes 类 */
public function load_class($class){
    static $objects_m = array();
    if (isset($objects_m[$class])) {
        return $objects_m[$class];
    }
    return self::__autoload($class);
}

/* 加载 业务 classes 类 */
public function load_class_wz($class){
    static $objects_m = array();
    if (isset($objects_m[$class])) {
        return $objects_m[$class];
    }
    return self::__autoload($class,'wzclasses/');
}

/* 回调函数 */
public static function insert_func_array($controller_arr){
    $fun_arr = isset($controller_arr['fun_arr']) ? $controller_arr['fun_arr'] : array();
    $dir = empty($controller_arr['dir']) ? '' : $controller_arr['dir'];
    $clss = new $controller_arr['name']($dir);
    call_user_func_array(array(& $clss, $controller_arr['method']), $fun_arr);
}

function __autoload($class_name, $type = 'classes/'){
    if (file_exists(LIB . $type . $class_name . EXT)) {
        require_once LIB . $type . $class_name . EXT;
        $objects_m[$class_name] = new $class_name();
        //$objects_m[$model] = $model::get_instance();//如果PHP版本5.3+
        return $objects_m[$class_name];
    } else {
        return FALSE;
    }
}


/* 加载CSS */
public function load_css($str){
    if (is_array($str)) {
        foreach ($str as $key => $val) {
            echo '<link href="' . CSS_PATH . $val . '.css?' . md5(SYS_VERSION) . '.css" rel="stylesheet" type="text/css" />';
        }
    } else {
        echo '<link href="' . CSS_PATH . $str . '.css?' . md5(SYS_VERSION) . '" rel="stylesheet" type="text/css" />';
    }
}

/* 加载JS */
public function load_js($str){
    if (is_array($str)) {
        foreach ($str as $key => $val) {
            echo '<script type="text/javascript" src="' . JS_PATH . $val . '.js?' . md5(SYS_VERSION) . '.js" charset="utf-8"></script>';
        }
    } else {
        echo '<script type="text/javascript" src="' . JS_PATH . $str . '.js?' . md5(SYS_VERSION) . '.js" charset="utf-8"></script>';
    }
}


//-- 分页 --
public function page_bar($count, $size, $url = '', $num = 9, $parameter = 'p'){
    if($count<=0 || $size<=0){
        return;   
    }
    $url = empty($url) ? $_SERVER['REQUEST_URI'] . '' : $url . '';
    if (!stripos($url, $parameter . '=')) {
        if (strpos($url, '/?')) {
            $page_url = $url . '&' . $parameter . '=';
        } else {
            $page_url = $url . '/?' . $parameter . '=';
        }
    } else {
        $page_url = substr($url, 0, (strripos($url, $parameter . '=') + strlen($parameter . '=')));
    }
    $p = empty($_GET[$parameter]) ? 1 : $_GET[$parameter];
    $toall = ceil($count / $size);
    $toall_str = '<span>共' . $toall . '页' . $count . '条记录</span>';
    if ($p > $toall) $p = $toall;
    $str = '';
    $frist = ($p <= 1) ? '<strong>首页</strong>' : '<a href="' . $page_url . '1" title="首页">首页</a>';
    //$pre = ($p <= 1) ? '<a href="'.$page_url.'1" title="上一页">上一页</a>' : '<a href="'.$page_url.($p-1).'" title="上一页">上一页</a>';
    //$next = ($p >= $toall) ? '<a href="'.$page_url.$toall.'" title="下一页">下一页a</a>' : '<a href="'.$page_url.($p+1).'" title="下一页">下一页</a>';
    $pre = ($p <= 1) ? '' : '<a href="' . $page_url . ($p - 1) . '" title="上一页">上一页</a>';
    $next = ($p >= $toall) ? '' : '<a href="' . $page_url . ($p + 1) . '" title="下一页">下一页</a>';
    $last = ($p >= $toall) ? '<strong>尾页</strong>' : '<a href="' . $page_url . $toall . '" title="尾页">尾页</a>';
    if ($toall <= $num) {
        for ($i = 1; $i <= $toall; $i++) {
            if ($p == $i) {
                $str .= '<strong>' . $i . '</strong>';
            } else {
                $str .= '<a href="' . $page_url . $i . '">' . $i . '</a>';
            }
        }
        if ($toall <= 1) {
            return;
        } else {
            return '<div class="webpages">' . $frist . $pre . $str . $next . $last . $toall_str . '</div>';
        }
    }
    if (($toall - $p) > ceil($num / 2) && $p < ceil($num / 2)) {
        for ($i = 1; $i <= $num; $i++) {
            if ($p == $i) {
                $str .= '<strong>' . $i . '</strong>';
            } else {
                $str .= '<a href="' . $page_url . $i . '">' . $i . '</a>';
            }
        }

        if ($toall <= 1) {
            return;
        } else {
            return '<div class="webpages">' . $frist . $pre . $str . $next . $last . $toall_str . '</div>';
        }
    }
    if (($toall - $p) < ceil($num / 2)) {
        for ($i = ($toall - $num + 1); $i <= $toall; $i++) {
            if ($p == $i) {
                $str .= '<strong>' . $i . '</strong>';
            } else {
                $str .= '<a href="' . $page_url . $i . '">' . $i . '</a>';
            }
        }
        return '<div class="webpages">' . $frist . $pre . $str . $next . $last . $toall_str . '</div>';
    }
    for ($i = ($p - floor($num / 2)); $i <= ($p + floor($num / 2)); $i++) {
        if ($p == $i) {
            $str .= '<strong>' . $i . '</strong>';
        } else {
            $str .= '<a href="' . $page_url . $i . '">' . $i . '</a>';
        }
    }
    if ($toall <= 1) {
        return;
    } else {
        return '<div class="webpages">' . $frist . $pre . $str . $next . $last . $toall_str . '</div>';
    }
}

//curl请求
public function https_request($url, $data=null, $type=''){
    if($type=='json'){//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
        $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
        $data=json_encode($data);
    }
    $curl = curl_init();  
    curl_setopt($curl, CURLOPT_URL, $url);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  
    if (!empty($data)){  
        curl_setopt($curl, CURLOPT_POST, 1);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
    }  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    $output = curl_exec($curl);  
    curl_close($curl);  
    return $output;
}

//curl返回get请求地址的请求状态
public function curl_get($url){  
    $curl = curl_init();  
    curl_setopt($curl, CURLOPT_URL, $url);  
    curl_setopt($curl, CURLOPT_HEADER, FALSE);  
    curl_setopt($curl, CURLOPT_NOBODY, FALSE);
    curl_setopt($curl, CURLOPT_TIMEOUT,30); //默认30秒超时  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);  
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');  
    $output = curl_exec($curl);  
    $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);  
    curl_close($curl);  
    return $httpCode;
}

//转议文件地址
public function file_path($file){    
    if(empty($file)){
       return false; 
    }
    $site_config = site_config();
    $file = $site_config['file_url'].'/'.$file;
    return $file;
}

//生成小图
public function img($url, $width, $height, $default_none_pic ='nopic'){
    $site_config = site_config();
    if (!empty($url)){
        $url=str_replace('/upload/','upload/',$url); //避免上传有些带/,有些不带
        $ext_temp = explode('.', $url);
        $ext = $ext_temp[1];
        $url_arr = explode('_', $ext_temp[0]);

        $webpicpath=$site_config['file_url'].'/thumb/';
        $picpath = $url_arr[0] .'_w'.$width.'_h'.$height.'.'.$ext;
        return str_replace('upload/', $webpicpath, $picpath);
    }else{
        return $site_config['file_url'].'/static/images/'.$default_none_pic.'.gif';
    }
}

//根据小图算出原图
public function img_to_source($url){    
    if(empty($url)){
       return false; 
    }
    $site_config = site_config();
    $ext = substr($url,strrpos($url,'.')+1);

    $webpicpath = $site_config['file_url'].'/thumb/';
    $url = str_replace($webpicpath, 'upload/', $url);
    $url_temp = explode('_w', $url);
    $url = $url_temp[0] .'.'.  $ext;
    return $url;
}

//前端站，把abc_w0_h0.jpg转义成abc_w120_h120.jpg
public function diyimg($url,$width, $height){
    if($url){
        $url = str_replace('_w0','_w'.$width, $url);
        $url = str_replace('_h0','_h'.$height, $url);
    }else{
        $url = IMG_PATH . 'nopic.gif';
    }
    return $url;
}


public function diy_delFile($source_file=''){
    if(empty($source_file)){
        return array('code' =>'Error', 'msg' =>'需要删除的文件不能为空');
    }
    $path_arr = explode('/', $source_file);//分离路径和文件
    $file = $path_arr[ count($path_arr)-1 ];//文件
    $ext_temp = explode('.', $file);
    $filename = $ext_temp[0];//扩展名
    $ext = $ext_temp[1];//文件名
    
    array_pop($path_arr);   //删除数组中的最后一个元素
    $path = '';
    foreach ($path_arr as $k => $v) {
        $path .=$v.'/';
    }
    $small_pic_path = str_replace('upload/', 'thumb/', $path);//得到小图路径

    if(is_dir($small_pic_path)){//目录存在
        $small_pic_lists = scandir($small_pic_path);//读取小图路径下所有文件
        foreach ($small_pic_lists as $k => $v){
            if(strpos($v, $filename) !== false){ //包含
                @unlink($small_pic_path.$v); 
            }
        }
    }
    @unlink($source_file);
    return array('code' =>'Success', 'msg' =>'全部删除成功');
}


//fsocket模拟get提交： url为地址，post参数，同步或异步，默认为异步。
public function sock_get($url='', $query_arr='', $synctype='async'){
    $data='';  
    $query_str = http_build_query($query_arr);
    $info = parse_url($url);
    $fp = fsockopen($info["host"], 80, $errno, $errstr, 3);
    $head = "GET ".$info['path']."?".$query_str." HTTP/1.0\r\n";
    $head .= "Host: ".$info['host']."\r\n";
    $head .= "\r\n";
    $write = fputs($fp, $head);
    if($synctype=='sync'){//同步时，等待结果
        while (!feof($fp)){
            $data[] = fread($fp,4096);
        }
    }
    fclose($fp);
    return $data;
}

//fsockopen post方法： url为地址，post参数，同步或异步，默认为异步。
public function sock_post($url, $query_arr, $synctype='async'){
    $data='';
    $query_str = http_build_query($query_arr);
    $info = parse_url($url);
    $fp = fsockopen($info["host"], 80, $errno, $errstr, 3);
    if(!empty($info["query"])){
        $head = "POST ".$info['path']."?".$info["query"]." HTTP/1.0\r\n";
    }else{
        $head = "POST ".$info['path']." HTTP/1.0\r\n";    
    }
    $head .= "Host: ".$info['host']."\r\n";
    $head .= "Referer: http://".$info['host'].$info['path']."\r\n";
    $head .= "Content-type: application/x-www-form-urlencoded\r\n";
    $head .= "Content-Length: ".strlen(trim($query_str))."\r\n";
    $head .= "\r\n";
    $head .= trim($query_str);
    $write = fputs($fp, $head);
    if($synctype=='sync'){//同步时，等待结果
        while (!feof($fp)){
            $data[] = fread($fp,4096);
        }
    }
    fclose($fp);
    return $data;
}



/*移动端判断*/
public function isMobile(){
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])){ 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 

    $USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($USER_AGENT)){
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($USER_AGENT))){
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])){ 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
            return true;
        } 
    } 
    return false;
}


}
