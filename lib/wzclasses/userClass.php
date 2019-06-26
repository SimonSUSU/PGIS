<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* remark : 用户相关 */
class userClass extends Controllers{
	function __construct($dir = ''){
        parent::__construct();
	}

    //列表
    public function lists($post, $field='*'){
        $keyword = !empty($post['keyword'])? addslashes(trim($post['keyword'])) :'';
        $start_date = !empty($post['start_date'])? strtotime($post['start_date'].' 00:00:00') :'';
        $end_date = !empty($post['end_date'])? strtotime($post['end_date'].' 23:59:59') :'';

        $start_login_date = !empty($post['start_login_date'])? strtotime($post['start_login_date'].' 00:00:00') :'';
        $end_login_date = !empty($post['end_login_date'])? strtotime($post['end_login_date'].' 23:59:59') :'';

        $pageSize = empty($post['pagesize']) ? $this->_page : $post['pagesize'];
        $p = empty($post['page']) ? 0 : $post['page'];
        $offset = ($p <= 0) ? 0 : ($p - 1) * $pageSize;

        $where = '';

        if (!empty($post['is_householder'])){   //是否户主：1不是，2是
            $where .= ' AND is_householder='.$post['is_householder'];
        } 
        if (!empty($post['label'])){    //1未脱贫，2已脱贫
            $where .= ' AND label='.$post['label'];
        }
        if (!empty($post['type'])){ //类型：1贫困户，2非贫因户（需要配合村民字段使用）
            $where .= ' AND type='.$post['type'];
        }

        if (!empty($post['status'])){
            $where .= ' AND status='.$post['status'];
        }        
        if (!empty($post['sex'])){
            $where .= ' AND sex='.$post['sex'];
        }        
        if (!empty($post['purviewgroup_id'])){
            $where .= ' AND purviewgroup_id='.$post['purviewgroup_id'];
        }
        if (!empty($post['is_villager'])){
            $where .= ' AND is_villager='.$post['is_villager'];
        }
        if (!empty($post['is_villagecadre'])){
            $where .= ' AND is_villagecadre='.$post['is_villagecadre'];
        }
        if (!empty($post['is_towncadre'])){
            $where .= ' AND is_towncadre='.$post['is_towncadre'];
        }
        if (!empty($post['is_platform'])){
            $where .= ' AND is_platform='.$post['is_platform'];
        }
        if (!empty($post['is_pricebureau'])){
            $where .= ' AND is_pricebureau='.$post['is_pricebureau'];
        }
        if (!empty($post['is_finance'])){
            $where .= ' AND is_finance='.$post['is_finance'];
        }
        if (!empty($post['weixin_sex'])){
            $where .= ' AND weixin_sex='.$post['weixin_sex'];
        }

        if (!empty($post['sn'])){
            $where .= ' AND sn="'.$post['sn'].'"';
        }

        if (!empty($post['phone'])){
            $where .= ' AND phone="'.$post['phone'].'"';
        }

        if (!empty($post['area_id']) && !empty((int)$post['area_id'])){
            $area_data_rs = $this->db->select(' ', 'area_id,pid','wz_area');
            $this->area_Obj = $this->load_class_wz('areaClass');
            $area_id_arr = $this->area_Obj->scanNodeOfTree($area_data_rs, $post['area_id']);
            $area_id_arr[] = $post['area_id'];
            $where .= ' AND area_id IN('. implode(',', $area_id_arr) .')';
        }

        if (isset($post['user_ids'])){
            $where .= ' AND user_id IN('. implode(',', $post['user_ids']) .')';//多个时用IN查
        }

        if(!empty($start_date)){
            $where.=' AND add_time>='.$start_date.' ';
        }
        if(!empty($end_date)){
            $where.=' AND add_time<='.$end_date.' ';
        }

        if(!empty($start_login_date)){
            $where.=' AND last_login_time>='.$start_login_date.' ';
        }
        if(!empty($end_login_date)){
            $where.=' AND last_login_time<='.$end_login_date.' ';
        }

        if (!empty($keyword)){
            $where .= " AND ( realName LIKE '%" . $keyword ."%' OR phone LIKE '%" . $keyword ."%' ) ";
        }

        $where_count = $where;
        if(isset($post['order'])) {
            switch ($post['order']){
                case 'asc':
                $order =' ASC ';
                break;

                case 'desc':
                    $order =' DESC ';
                break;

                default:
                    $order =' DESC ';
                break;
            }
        }else{
          $order =' DESC '; 
        }
         
        if(isset($post['sort'])) {
            switch ($post['sort']){
            	case 'area_id'://所属地区
                    $where .=' ORDER BY area_id '.$order.', user_id '.$order;
                break;               

                case 'sex_str'://sex
                    $where .=' ORDER BY sex '.$order.', user_id '.$order;
                break;

                case 'birth'://birth
                    $where .=' ORDER BY birth '.$order.', user_id '.$order;
                break;

                case 'status_str'://状态
                    $where .=' ORDER BY status '.$order.', user_id '.$order;
                break;

                case 'points_total'://积分量
                    $where .=' ORDER BY points_total '.$order.', user_id '.$order;
                break;

                case 'purviewgroup_id'://权限组
                    $where .=' ORDER BY purviewgroup_id '.$order.', user_id '.$order;
                break;

                case 'add_time_str'://注册时间
                    $where .=' ORDER BY add_time '.$order.', user_id '.$order;
                break;

                case 'last_login_time_str'://最后登录时间
                    $where .=' ORDER BY last_login_time '.$order.', user_id '.$order;
                break;

                case 'last_time_str'://最后修改日期
                    $where .=' ORDER BY last_time '.$order.', user_id '.$order;
                break;

                case 'weixin_lastupdate_time_str'://微信资料更新时间
                    $where .=' ORDER BY weixin_lastupdate_time '.$order.', user_id '.$order;
                break;

                case 'weixin_subscribe_time_str'://微信关注时间
                    $where .=' ORDER BY weixin_subscribe_time '.$order.', user_id '.$order;
                break;                

                case 'is_villager_str'://是否村民：0其它，1是贫因户，2非贫因户
                    $where .=' ORDER BY is_villager '.$order.', user_id '.$order;
                break;
                case 'is_villagecadre_str'://是否村干部：0否，1是
                    $where .=' ORDER BY is_villagecadre '.$order.', user_id '.$order;
                break;
                case 'is_towncadre_str'://是否是乡镇干部：0否，1是
                    $where .=' ORDER BY is_towncadre '.$order.', user_id '.$order;
                break;
                case 'is_platform_str'://是否电商办工作人员：0否，1是
                    $where .=' ORDER BY is_platform '.$order.', user_id '.$order;
                break;
                case 'is_pricebureau_str'://是否是物价局工作人员：1否，1是
                    $where .=' ORDER BY is_pricebureau '.$order.', user_id '.$order;
                break;
                case 'is_finance_str'://是否财政局工作人员：0否，1是
                    $where .=' ORDER BY is_finance '.$order.', user_id '.$order;
                break;

                case 'label_str'://1未脱贫，2已脱贫
                    $where .=' ORDER BY label '.$order.', user_id '.$order;
                break;
                case 'type_str'://类型：1贫困户，2非贫因户
                    $where .=' ORDER BY type '.$order.', user_id '.$order;
                break;
                case 'is_householder_str'://是否户主：1不是，2是
                    $where .=' ORDER BY is_householder '.$order.', user_id '.$order;
                break;
                
                default:
                    $where .=' ORDER BY user_id '.$order; 
                break;
            }
        }else{
           $where .=' ORDER BY user_id '.$order; 
        }

        $rs = $this->db->select(' WHERE 1=1 ', $field, 'wz_user', 0, array($offset, $pageSize), $where);
        if(empty($rs)){
            return array('code' =>'Empty', 'msg' => '没有符合条件的用户');
        }
        
        //$user_id_arr = array();
        $purviewgroup_id_arr = array();
        $area_id_arr = array();
        foreach ($rs as $k => $v){
        	//if(isset($v['user_id']) && !empty($v['user_id'])){
            //    $user_id_arr[] = $v['user_id'];
            //}
            if(isset($v['purviewgroup_id']) && !empty($v['purviewgroup_id'])){//权限组
                $purviewgroup_id_arr[] = $v['purviewgroup_id'];
            }
            if(isset($v['area_id']) && !empty($v['area_id'])){
                $area_id_arr[] = $v['area_id'];
            }
        }

        if(!empty($purviewgroup_id_arr)){
            $purviewgroup_rs = $this->db->select(array('purviewgroup_id' =>$purviewgroup_id_arr),'purviewgroup_id,name', 'wz_purviewgroup','purviewgroup_id');
        }
        if(!empty($area_id_arr)){
            $areaArr = $this->db->select('','area_id as id, pid,name','wz_area'); //因为多级需要计算，所以查出所有  
        }

        foreach ($rs as $k => $v){
            if(isset($v['area_id']) && !empty($v['area_id'])){
                $rs[$k]['area_rs'] = genTree($areaArr, $v['area_id'], 'parent');

                $areaStr = '';
                if(!empty($rs[$k]['area_rs'])){
                    foreach($rs[$k]['area_rs'] as $kk=>$vv){
                        $areaStr = $vv['name'].'/'.$areaStr;
                    }
                    $areaStr = trim($areaStr,'/');
                    $rs[$k]['area_str'] = $areaStr;
                }
            }

            if(isset($v['purviewgroup_id']) && !empty($v['purviewgroup_id'])  && !empty($purviewgroup_rs[$v['purviewgroup_id']])  ){//权限组
                $rs[$k]['purviewgroup_arr'] = $purviewgroup_rs[$v['purviewgroup_id']];
            }

            if(isset($v['pics']) && !empty($v['pics'])){
                $pics = explode(',',$v['pics']);
                if(!empty($pics)){
                    foreach ($pics as $kk => $vv){
                        if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
                            $pics[$kk] = $this->img($vv,$post['imgWidth'],$post['imgHeight']);
                        }else{
                            $pics[$kk] = $this->img($vv,0,0);
                        }
                    }
                    $rs[$k]['pics'] = $pics;
                }
            }

            if(!empty($v['birth'])){
                $rs[$k]['age'] = get_age($v['birth']);
            }

            if(isset($v['is_villager'])){
                $rs[$k]['is_villager_str'] = $this->user_attribute_arr[$v['is_villager']];
            }
            if(isset($v['is_villagecadre'])){
                $rs[$k]['is_villagecadre_str'] = $this->user_attribute_arr[$v['is_villagecadre']];
            }

            if(isset($v['is_towncadre'])){
                $rs[$k]['is_towncadre_str'] = $this->user_attribute_arr[$v['is_towncadre']];
            }
            if(isset($v['is_platform'])){
                $rs[$k]['is_platform_str'] = $this->user_attribute_arr[$v['is_platform']];
            }
            if(isset($v['is_pricebureau'])){
                $rs[$k]['is_pricebureau_str'] = $this->user_attribute_arr[$v['is_pricebureau']];
            }
            if(isset($v['is_finance'])){
                $rs[$k]['is_finance_str'] = $this->user_attribute_arr[$v['is_finance']];
            }
            if(isset($v['sex'])){
                $rs[$k]['sex_str'] = $this->sex_arr[$v['sex']];
            }

            if(isset($v['weixin_sex'])){
                $rs[$k]['weixin_sex_str'] = !empty($this->sex_arr[$v['weixin_sex']]) ? $this->sex_arr[$v['weixin_sex']]:'';
            }
            if(isset($v['status'])){
                $rs[$k]['status_str'] = $this->status_arr[$v['status']];
            }

            if(isset($v['is_householder'])){
                $rs[$k]['is_householder_str'] = $this->householder_arr[$v['is_householder']];
            }
            if(isset($v['label'])){
                $rs[$k]['label_str'] = $this->villager_label_arr[$v['label']];
            }
            if(isset($v['type'])){
                $rs[$k]['type_str'] = $this->villager_type_arr[$v['type']];
            }

            if(isset($v['weixin_lastupdate_time']) && !empty($v['weixin_lastupdate_time'])){//微信资料的最后更新时间
                $rs[$k]['weixin_lastupdate_time_str'] = date('Y-m-d H:i:s',$v['weixin_lastupdate_time']);
            }
            if(isset($v['weixin_subscribe_time']) && !empty($v['weixin_subscribe_time'])){	//微信关注时间
                $rs[$k]['weixin_subscribe_time_str'] = date('Y-m-d H:i:s',$v['weixin_subscribe_time']);
            }
            if(isset($v['last_login_time']) && !empty($v['last_login_time'])){
                $rs[$k]['last_login_time_str'] = date('Y-m-d H:i:s',$v['last_login_time']);
            }
            if(isset($v['add_time'])){
                $rs[$k]['add_time_str'] = date('Y-m-d H:i:s',$v['add_time']);
            }
            if(isset($v['last_time'])){
                $rs[$k]['last_time_str'] = date('Y-m-d H:i:s',$v['last_time']);
            }
        }
        $rs_toall = $this->db->select(' WHERE 1=1 ', 'COUNT(*) AS count, SUM(points_total) AS points','wz_user',0,'',$where_count);
        $page['toall'] = $rs_toall[0]['count'];
        $page['points'] = $rs_toall[0]['points'];
        $page['total_page'] = ceil($rs_toall[0]['count'] / $pageSize);
        $page['pageSize'] = $pageSize;
        $page['page'] = $p;
        return array('code' =>'Success', 'item' => $rs, 'page'=>$page );
    }

    //详细
    public function view($post, $field='*'){
        if(empty($post['user_id'])){
            return array('code' =>'Error', 'msg' => '用户ID不能为空');
        }
        $rs = $this->db->select(array('user_id' =>$post['user_id']), $field, 'wz_user');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '用户不存在');
        }
        if(isset($rs[0]['purviewgroup_id']) && !empty($rs[0]['purviewgroup_id'])){//权限组
            $purviewgroup_rs = $this->db->select(array('purviewgroup_id' =>$rs[0]['purviewgroup_id']),'purviewgroup_id,name', 'wz_purviewgroup');
            if(!empty($purviewgroup_rs)){
                $rs[0]['purviewgroup_arr'] = $purviewgroup_rs[0];
            }
        }

        if(isset($rs[0]['pics'])){
            $pics = explode(',',$rs[0]['pics']);
            if(!empty($pics)){
                foreach ($pics as $kk => $vv){
                    if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
                        $pics[$kk] = $this->img($vv,$post['imgWidth'],$post['imgHeight']);
                    }else{
                        $pics[$kk] = $this->img($vv,0,0);
                    }
                }
                $rs[0]['pics'] = $pics;
            }
        }

        if(!empty($rs[0]['area_id'])){
            $areaArr = $this->db->select('','area_id as id, pid,name','wz_area'); //因为多级需要计算，所以查出所有 
            $rs[0]['area_rs'] = genTree($areaArr, $rs[0]['area_id'], 'parent');      
            $areaStr = '';
            if(!empty($rs[0]['area_rs'])){
                foreach($rs[0]['area_rs'] as $kk=>$vv){
                    $areaStr = $vv['name'].'/'.$areaStr;
                    if($vv['pid']==0){
                        $rs[0]['area_root_id'] = $vv['id'];
                    }
                }
                $areaStr = trim($areaStr,'/');
                $rs[0]['area_str'] = $areaStr;
            }
        }

        if(isset($rs[0]['idcard_front']) && !empty($rs[0]['idcard_front'])){
            if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
                $rs[0]['idcard_front'] = $this->img($rs[0]['idcard_front'],$post['imgWidth'],$post['imgHeight']);
            }else{
                $rs[0]['idcard_front'] = $this->img($rs[0]['idcard_front'],0,0);
            }
        }
        if(isset($rs[0]['idcard_back']) && !empty($rs[0]['idcard_back'])){
            if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
                $rs[0]['idcard_back'] = $this->img($rs[0]['idcard_back'],$post['imgWidth'],$post['imgHeight']);
            }else{
                $rs[0]['idcard_back'] = $this->img($rs[0]['idcard_back'],0,0);
            }
        }

        if(!empty($rs[0]['birth'])){
            $rs[0]['age'] = get_age($rs[0]['birth']);
        }
                
        if(isset($rs[0]['is_villager'])){
            $rs[0]['is_villager_str'] = $this->user_attribute_arr[$rs[0]['is_villager']];
        }

        if(isset($rs[0]['is_villagecadre'])){
            $rs[0]['is_villagecadre_str'] =  $this->user_attribute_arr[$rs[0]['is_villagecadre']];
        }

        if(isset($rs[0]['is_towncadre'])){
            $rs[0]['is_towncadre_str'] = $this->user_attribute_arr[$rs[0]['is_towncadre']];
        }

        if(isset($rs[0]['is_platform'])){
            $rs[0]['is_platform_str'] =  $this->user_attribute_arr[$rs[0]['is_platform']];
        }

        if(isset($rs[0]['is_pricebureau'])){
            $rs[0]['is_pricebureau_str'] = $this->user_attribute_arr[$rs[0]['is_pricebureau']];
        }

        if(isset($rs[0]['is_finance'])){
            $rs[0]['is_finance_str'] =  $this->user_attribute_arr[$rs[0]['is_finance']];
        }

        if(isset($rs[0]['sex'])){
            $rs[0]['sex_str'] = $this->sex_arr[$rs[0]['sex']];
        }

        if(isset($rs[0]['weixin_sex'])){
            $rs[0]['weixin_sex_str'] = $this->sex_arr[$rs[0]['weixin_sex']];
        }

        if(isset($rs[0]['status'])){
            $rs[0]['status_str'] = $this->status_arr[$rs[0]['status']];
        }

        if(isset($rs[0]['is_householder'])){
            $rs[0]['is_householder_str'] = $this->householder_arr[$rs[0]['is_householder']];
        }
        if(isset($rs[0]['label'])){
            $rs[0]['label_str'] = $this->villager_label_arr[$rs[0]['label']];
        }
        if(isset($rs[0]['type'])){
            $rs[0]['type_str'] = $this->villager_type_arr[$rs[0]['type']];
        }

        if(isset($rs[0]['weixin_lastupdate_time']) && !empty($rs[0]['weixin_lastupdate_time'])){//微信资料的最后更新时间
            $rs[0]['weixin_lastupdate_time_str'] = date('Y-m-d H:i:s',$rs[0]['weixin_lastupdate_time']);
        }

        if(isset($rs[0]['weixin_subscribe_time']) && !empty($rs[0]['weixin_subscribe_time'])){	//微信关注时间
            $rs[0]['weixin_subscribe_time_str'] = date('Y-m-d H:i:s',$rs[0]['weixin_subscribe_time']);
        }

        if(isset($rs[0]['last_login_time']) && !empty($rs[0]['last_login_time'])){
            $rs[0]['last_login_time_str'] = date('Y-m-d H:i:s',$rs[0]['last_login_time']);
        }

        if(isset($rs[0]['add_time'])){
            $rs[0]['add_time_str'] = date('Y-m-d H:i:s',$rs[0]['add_time']);
        }

        if(isset($rs[0]['last_time'])){
            $rs[0]['last_time_str'] = date('Y-m-d H:i:s',$rs[0]['last_time']);
        }
        return array('code' =>'Success', 'item' => $rs[0]);
    }

    //添加
    public function add($post, $files=''){
        if(empty($post['phone'])){
            return array('code' =>'Error', 'msg' => '手机号不能为空');
        }
        if(!is_phone($post['phone'])){
            return array('code' =>'Error', 'msg' => '手机号格式不正确');
        }
        if(empty($post['realName'])){
            return array('code' =>'Error', 'msg' => '姓名不能为空');
        }
        $rs = $this->db->select(array('phone' =>$post['phone']), 'user_id', 'wz_user');
        if(!empty($rs)){
            return array('code' =>'Error', 'msg' => '手机号'.$post['phone'].'已占用');
        }

        $time = time();
        $field_rs = $this->field($post,$files);//组装各字段
        if($field_rs['code'] !='Success'){
            return $field_rs;
        }
        $data = $field_rs['item'];
        $data['phone'] = $post['phone'];
        $data['add_time'] = $time;
        $data['last_time'] = $time;
        $data['last_login_time'] = $time;

        $rs = $this->db->insert($data,'wz_user');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '添加失败');
        }

        if(!empty($field_rs['item']['area_id']) || !empty($field_rs['item']['status'])){
            $this->update_user_area($rs,$field_rs['item']['area_id']);  //用户的地区变化，其它关联表也跟着更新
            $this->update_area_user_total($field_rs['item']['area_id']);  //更新地区人数
        }

        if(!empty($field_rs['item']['type'])){
            $this->update_user_type($rs, $field_rs['item']['type']);  //用户类型：2贫困户，1非贫因户，关联表也跟着更新
        }

        return array('code' =>'Success', 'item' => $rs);
    }

    //修改
    public function edit($post,$files=''){
        if(empty($post['user_id'])){
            return array('code' =>'Error', 'msg' => '用户ID不能为空');
        }

        $rs = $this->db->select(array('user_id' =>$post['user_id']), 'user_id,area_id,type,status,phone,idcard,pics,idcard_front,idcard_back,sn,is_householder,is_villager,weixin_openid,weixin_unionid', 'wz_user');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '用户不存在');
        }

        $field_rs = $this->field($post,$files,$rs[0]);//组装各字段
        if($field_rs['code'] !='Success'){
            return $field_rs;
        }
        $data = $field_rs['item'];

        if(isset($post['phone'])){
            if(empty($post['phone'])){
                return array('code' =>'Error', 'msg' => '手机号不能为空');
            }
            if(!is_phone($post['phone'])){
                return array('code' =>'Error', 'msg' => '手机号格式不正确');
            }
            if($rs[0]['phone'] != $post['phone'] ){
                $rs = $this->db->select(array('phone' =>$post['phone']), 'user_id', 'wz_user');
                if(!empty($rs)){
                    return array('code' =>'Error', 'msg' => '手机号'.$post['phone'].'已占用');
                }
                $data['phone'] = $post['phone'];
            }
        }

        if(empty($data)){
            return array('code' =>'Error', 'msg' => '修改项不能为空');
        }
        $data['last_time'] = time();
        $edit_rs = $this->db->edit($data, array('user_id'=>$post['user_id']), 'wz_user');
        if(empty($edit_rs)){
            return array('code' =>'Error', 'msg' => '无修改');
        }


        if(!empty($field_rs['item']['is_householder']) && $field_rs['item']['is_householder'] !=$rs[0]['is_householder']  ){
            $this->update_area_user_total($rs[0]['area_id'] );  //更新地区人数
        }

        if(!empty($field_rs['item']['area_id']) && $field_rs['item']['area_id'] !=$rs[0]['area_id']  ){
            $this->update_user_area($post['user_id'],$field_rs['item']['area_id']);  //用户的地区变化，其它关联表也跟着更新
            $this->update_area_user_total($field_rs['item']['area_id']);  //更新地区人数
            $this->update_area_user_total($rs[0]['area_id'] );  //更新地区人数
        }

        if(!empty($field_rs['item']['type']) && $field_rs['item']['type'] !=$rs[0]['type']  ){
            $this->update_user_type($post['user_id'], $rs[0]['type']);  //用户类型：2贫困户，1非贫因户，关联表也跟着更新
            $this->update_user_type($post['user_id'], $field_rs['item']['type']);  //用户类型：2贫困户，1非贫因户，关联表也跟着更新
        }

        if(!empty($field_rs['item']['status']) && $field_rs['item']['status'] !=$rs[0]['status'] ){ //状态变化时，地区人数也要变(是村民才变)
            $this->update_area_user_total($rs[0]['area_id'] );  //更新地区人数
        }
        return array('code' =>'Success', 'msg' => '修改成功');
    }

    //添加、修改的字段项
    public function field($post, $files='',$rs=''){
        $data = array();        
        // if(!empty($post['city_id']) && !isset($post['area_id']) ){
        //     $post['area_id'] = $post['city_id'];
        // }
        if(isset($post['area_id'])){
            if(!empty($post['area_id'])){
               $area_rs = $this->db->select(array('area_id' =>$post['area_id']),'area_id,level', 'wz_area');
                if(empty($area_rs)){
                    return array('code' =>'Error', 'msg' => '地区不存在');
                }
                if($area_rs[0]['level']!=3){
                    return array('code' =>'Error', 'msg' => '地区信息需选择村或区');
                } 
            }            
            $data['area_id'] = $post['area_id'];
        }

        if(isset($post['realName'])){
            if(empty($post['realName'])){
                return array('code' =>'Error', 'msg' => '真实姓名不能为空');
            }
            $realName = filterEmoji($post['realName']);
            $realName = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$realName);
            if(mb_strlen($realName,'utf8') <2 || mb_strlen($realName,'utf8') >20){
                return array('code' =>'Error', 'msg' => '真实姓名最少2个字，最多20个字');
            }
            $data['realName'] = $realName;
        }

        if(!empty($post['weixin_nickName'])){
            $weixin_nickName = filterEmoji($post['weixin_nickName']);
            $weixin_nickName = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$weixin_nickName);
            $data['weixin_nickName'] = $weixin_nickName;
        }

        if(isset($post['idcard'])){
        	if(!empty($post['idcard']) ){
        		if(!checkId($post['idcard'])){
        			return array('code' =>'Error', 'msg' => '身份证号不正确');
        		}
                if(!empty($rs['idcard'])){
                    if($rs['idcard'] != $post['idcard'] ){
                        $user_rs = $this->db->select(array('idcard' =>$post['idcard']), 'user_id', 'wz_user');
                        if(!empty($user_rs)){
                            return array('code' =>'Error', 'msg' => '身份证号已占用');
                        }
                    }
                }else{
                   $user_rs = $this->db->select(array('idcard' =>$post['idcard']), 'user_id', 'wz_user');
                    if(!empty($user_rs)){
                        return array('code' =>'Error', 'msg' => '身份证号已占用');
                    } 
                }
        		$data['sex'] = idcard_to_sex($post['idcard']); //通过身份证得到性别
        		$data['birth'] = idcard_to_birth($post['idcard']); //通过身份证得到出生日期
        	}
            $data['idcard'] = $post['idcard'];
        }

        if(isset($post['householder_id'])){
            $data['householder_id'] = $post['householder_id'];
        }
        if(isset($post['sn'])){
            $data['sn'] = $post['sn'];
        }
        if(isset($post['is_householder'])){
            $data['is_householder'] = $post['is_householder'];
            if($post['is_householder']==2){//是户主时，所属户主变为0
                $data['householder_id'] = 0;
            }
        }
        
        if(!empty($post['weixin_unionid'])){
            if(!empty($rs['weixin_unionid']) && $rs['weixin_unionid'] != $post['weixin_unionid'] ){
                $user_rs = $this->db->select(array('weixin_unionid' =>$post['weixin_unionid']), 'user_id', 'wz_user');
                if(!empty($user_rs)){
                    return array('code' =>'Error', 'msg' => '当前微信已绑定了其他账户，请先解绑');
                }
            }else{
                $user_rs = $this->db->select(array('weixin_unionid' =>$post['weixin_unionid']), 'user_id', 'wz_user');
                if(!empty($user_rs)){
                    return array('code' =>'Error', 'msg' => '当前微信已绑定了其他账户，请先解绑');
                }
            }
            $data['weixin_unionid'] = $post['weixin_unionid'];
        }
        if(!empty($post['weixin_openid'])){
            if(!empty($rs['weixin_openid']) && $rs['weixin_openid'] != $post['weixin_openid'] ){
                $user_rs = $this->db->select(array('weixin_openid' =>$post['weixin_openid']), 'user_id', 'wz_user');
                if(!empty($user_rs)){
                    return array('code' =>'Error', 'msg' => '当前微信已绑定了其他账户，请先解绑');
                }
            }else{
                $user_rs = $this->db->select(array('weixin_openid' =>$post['weixin_openid']), 'user_id', 'wz_user');
                if(!empty($user_rs)){
                    return array('code' =>'Error', 'msg' => '当前微信已绑定了其他账户，请先解绑');
                }  
            }
            $data['weixin_openid'] = $post['weixin_openid'];
        }
        if(!empty($post['weixin_headimgurl'])){
            $data['weixin_headimgurl'] = $post['weixin_headimgurl'];
        }
        if(!empty($post['weixin_lastupdate_time'])){
            $data['weixin_lastupdate_time'] = $post['weixin_lastupdate_time'];
        }
        if(!empty($post['weixin_sex'])){
            $data['weixin_sex'] = $post['weixin_sex'];
        }
        if(!empty($post['weixin_city'])){
            $data['weixin_city'] = $post['weixin_city'];
        }
        if(!empty($post['weixin_province'])){
            $data['weixin_province'] = $post['weixin_province'];
        }
        if(!empty($post['weixin_country'])){
            $data['weixin_country'] = $post['weixin_country'];
        }
        if(!empty($post['weixin_language'])){
            $data['weixin_language'] = $post['weixin_language'];
        }
        if(!empty($post['weixin_subscribe_time'])){
            $data['weixin_subscribe_time'] = $post['weixin_subscribe_time'];
        }

        if(isset($post['purviewgroup_id'])){
            $data['purviewgroup_id'] = $post['purviewgroup_id'];
        }
        if(!empty($post['last_login_time'])){
            $data['last_login_time'] = $post['last_login_time'];
        }
        if(isset($post['points_total'])){
            $data['points_total'] = $post['points_total'];
        }
        if(isset($post['status'])){
            $data['status'] = $post['status'];
        }

        if(isset($post['is_villager'])){
            $data['is_villager'] = $post['is_villager'];
        }
        if(isset($post['is_villagecadre'])){
            $data['is_villagecadre'] = $post['is_villagecadre'];
        }
        if(isset($post['is_towncadre'])){
            $data['is_towncadre'] = $post['is_towncadre'];
        }
        if(isset($post['is_platform'])){
            $data['is_platform'] = $post['is_platform'];
        }
        if(isset($post['is_pricebureau'])){
            $data['is_pricebureau'] = $post['is_pricebureau'];
        }
        if(isset($post['is_finance'])){
            $data['is_finance'] = $post['is_finance'];
        }
        
        if(isset($post['longitude'])){
            $data['longitude'] = $post['longitude'];
        }
        if(isset($post['latitude'])){
            $data['latitude'] = $post['latitude'];
        }        
        
        if(isset($post['label'])){
            $data['label'] = $post['label'];
        }
        if(isset($post['type'])){
            $data['type'] = $post['type'];
        }
        if(isset($post['remark'])){
            $data['remark'] = $post['remark'];
        }

        if(!empty($files)){//当上传的图片不为空时，
            $uploadObj = $this->load_class('upload');

            if( !empty($files['idcard_front']['tmp_name']) ){
                $result = $uploadObj->upload_file($files['idcard_front'],'idcard');
                if(!empty($result['code']) && $result['code']=='Error'){
                    return $result; //上传错时
                }
                if(!empty($result)){
                    $data['idcard_front'] = $result;
                }
            }

            if( !empty($files['idcard_back']['tmp_name']) ){
                $result = $uploadObj->upload_file($files['idcard_back'],'idcard');
                if(!empty($result['code']) && $result['code']=='Error'){
                    return $result; //上传错时
                }
                if(!empty($result)){
                    $data['idcard_back'] = $result;
                }
            }

            if( !empty($files['pics']) ){
                $temp_pics_arr = array();
                foreach ($files['pics'] as $k => $file){
                    if( !empty($file['tmp_name']) ){
                        $result = $uploadObj->upload_file($file,'villager');
                        if(!empty($result['code']) && $result['code']=='Error'){
                            return $result; //上传错时
                        }
                        if(!empty($result)){            
                            $temp_pics_arr[] = $result;
                        }
                    }
                }
                //将上传后的图片数组去重，序列化存入字段中
                if(!empty($temp_pics_arr)){
                    if(!empty($rs['pics'])){ //如果原来图片不为空时
                        $old_pic_arr = explode(',',$rs['pics']);//反序列化
                        $temp_pics_arr = array_merge($old_pic_arr,$temp_pics_arr); //合并数组
                        $temp_pics_arr = array_unique($temp_pics_arr);//数组去重
                    }
                    $data['pics'] = implode(',',$temp_pics_arr);
                }
            }
        }
        return array('code' =>'Success', 'item' =>$data);
    }

    //删除
    public function del($post){
        if(empty($post['user_id'])){
            return array('code' =>'Error', 'msg' => '用户ID不能为空');
        }
        $rs = $this->db->edit(array('status'=>3), array('user_id'=>$post['user_id']), 'wz_user');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '删除失败');
        }
        return array('code' =>'Success', 'item' => $rs, 'msg' => '删除成功');
    }

    //注册
    public function reg($post){
        if(empty($post['realName'])){
            return array('code' =>'Error', 'msg' => '真实姓名不能为空');
        }
        if(empty($post['phone'])){
            return array('code' =>'Error', 'msg' => '手机号不能为空');
        }
        if(empty($post['code'])){
            return array('code' =>'Error', 'msg' => '手机验证码不能为空');
        }

        $this->sendsms_Obj = $this->load_class_wz('sendsmsClass');
        $check_rs = $this->sendsms_Obj->check(array('phone'=>$post['phone'],'code'=>$post['code']));
        if($check_rs['code'] !='Success'){
            return $check_rs;
        }
        $add_rs = $this->add($post);
        if($add_rs['code']!='Success'){
            return $add_rs;
        }
        return $this->uptoken(array('user_id'=>$add_rs['item']));
    }


    //用手机号和验证码登录
    public function login($post){
        $this->log_Obj = $this->load_class_wz('logClass');

        if( empty($post['phone'])  || empty($post['code']) ){
            return array('code' =>'Error', 'msg' => '手机号或验证码不能为空');
        }
        $phone = addslashes(trim($post['phone']));
        $code = addslashes(trim($post['code']));

        if(!is_phone($phone)){
            return array('code' =>'Error', 'msg' => '手机号格式不正确');
        }

        $this->cache_Obj = $this->load_class('WEBcache');
        /* 限制5分钟内连续5次错误时，锁定帐号5分钟 开始 */                      
        $d[] =time();
        $userLoginLock = $this->cache_Obj->getCache('userLoginLock_'.$phone);
        if(!empty($userLoginLock)){
            $d = array_merge($d,$userLoginLock);
        }
        $this->cache_Obj->setCache('userLoginLock_'.$phone, $d, 0, 60*5);//5分钟有效
        if( count($userLoginLock)>5 ){
            $this->log_Obj->login_log_add(array('account'=>$phone,'type'=>1,'status'=>2,'content'=>'你的帐号已连续'.count($userLoginLock).'次登录错误！已被系统锁定5分钟！'));//写入登录日志
            return array('code' =>'Error', 'msg' => '你的帐号已连续'.count($userLoginLock).'次登录错误，已被系统锁定5分钟！');
        }
        /* 限制5分钟内连续5次错误时，锁定帐号5分钟 结束 */

        $this->sendsms_obj = $this->load_class_wz('sendsmsClass');
        $rs = $this->sendsms_obj->check(array('phone'=>$phone, 'code'=>$code));
        if($rs['code'] !='Success'){
            return $rs; //短信和验证码不对  
        }

        $rs = $this->db->select(array('phone'=>$phone), 'user_id,status', 'wz_user');
        if(empty($rs)){//不存在
            $this->log_Obj->login_log_add(array('account'=>$phone,'type'=>1,'status'=>2,'content'=>'尚未注册，请先注册！'));//写入登录日志
            return array('code' =>'Error', 'msg' => '尚未注册，请先注册！');
        }

		if($rs[0]['status'] !=1){
            $this->log_Obj->login_log_add(array('account'=>$phone,'user_id'=>$rs[0]['user_id'], 'type'=>1, 'status'=>2,'content'=>'当前账户未启用，暂无法使用'));//写入登录日志
			return array('code' =>'Error', 'msg' => '当前账户未启用，暂无法使用');     
		}
        $user_view_rs = $this->uptoken(array('user_id'=>$rs[0]['user_id']));
        if($user_view_rs['code'] !='Success'){
            $this->log_Obj->login_log_add(array('account'=>$phone,'user_id'=>$rs[0]['user_id'], 'type'=>1, 'status'=>2,'content'=>$user_view_rs['msg']));//写入登录日志
        }

        $this->cache_Obj->delCache(md5('sendSMS'.$phone));  //登录成功时，删除短信验证码的记录
        $this->cache_Obj->delCache('userLoginLock_'.$phone); //登录成功时，删除登录锁            
        $this->log_Obj->login_log_add(array('account'=>$phone,'user_id'=>$rs[0]['user_id'], 'type'=>1, 'status'=>1,'content'=>'登录成功'));//写入登录日志
        $this->db->edit(array('last_login_time'=>time()), array('user_id'=>$user_view_rs['item']['user_id']), 'wz_user');//写入最后登录时间
        
        return $user_view_rs;
    }


    //刷新TOKEN
    public function uptoken($post){
        $rs = $this->view($post,'user_id,realName,phone,purviewgroup_id,add_time,last_login_time,status');
        
        if($rs['code']!='Success'){
            return array('code' =>'Error', 'msg' => '账号不存在');
        }
        if($rs['item']['status']!=1){
            return array('code' =>'Error', 'msg' => '账号未启用');
        }
        $site_config = site_config();  // 获取配置信息
        $url_key = $site_config['url_key'];
        $time = time();

        $token_key = md5($rs['item']['user_id'] . $url_key .$time);       
        $token_key_data = $rs['item']['user_id'].'|'. $token_key.'|'.$time;        
        $token = diy_encode($token_key_data, $url_key);//加密
        $rs['item']['token'] = $token;
        return $rs;
    }


    //删除图片
    public function delPic($post){
        if(empty($post['user_id'])){
            return array('code' =>'Error', 'msg' => 'user_id不能为空');
        }
        if(!isset($post['arryIndex'])){
            return array('code' =>'Error', 'msg' => '删除的图片序号不能为空');
        }
        $rs = $this->db->select(array('user_id' =>$post['user_id']),'user_id,pics', 'wz_user');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '信息不存在');
        }
        if(empty($rs[0]['pics'])){
            return array('code' =>'Error', 'msg' => '没有图片');   
        }
        $pics_arr = explode(',', $rs[0]['pics']);
        $del_pic = $pics_arr[$post['arryIndex']];
        unset($pics_arr[$post['arryIndex']]);//从数组中删除指定的图片
        $pics_arr = implode(',',$pics_arr);//序列化
        $rs = $this->db->edit(array('pics'=>$pics_arr), array('user_id'=>$post['user_id']), 'wz_user');
        if(empty($rs)){
           return array('code' =>'Error', 'msg' =>'删除图片失败');
        }
        $this->diy_delFile($del_pic);// 物理上删除原图和小图
        return array('code' =>'Success', 'msg' =>'删除图片成功');
    }

    //若用户的TYPE变化，那么用户其它表的也关联变（用于MYSQL空间换时间）
    public function update_user_type($user_id='',$type=''){
        $user_rs = $this->db->select(array('user_id' =>$user_id), 'user_id,area_id', 'wz_user');
        $this->update_area_user_total($user_rs[0]['area_id']); //更新地区人数

        $this->db->edit(array('user_type'=>$type), array('user_id'=>$user_id), 'wz_order'); //更新对应的作信息
        $this->db->edit(array('user_type'=>$type), array('user_id'=>$user_id), 'wz_user_points'); //更新对应的作信息
    }

    //若用户的地区变化，那么用户其它表的地区也关联变（用于MYSQL空间换时间）
    public function update_user_area($user_id='',$area_id=''){
        $this->db->edit(array('area_id'=>$area_id), array('user_id'=>$user_id), 'wz_order'); //更新对应的作答地区信息
        $this->db->edit(array('area_id'=>$area_id), array('user_id'=>$user_id), 'wz_user_points'); //更新对应的作答地区信息
        
        $this->db->edit(array('area_id'=>$area_id), array('user_id'=>$user_id), 'wz_user_assess'); //更新对应的作答地区信息       
        $this->db->edit(array('area_id'=>$area_id), array('user_id'=>$user_id), 'wz_user_assess_item'); //更新对应的作答地区信息
        $this->db->edit(array('area_id'=>$area_id), array('source_user_id'=>$user_id), 'wz_workflow'); //更新对应的评分审批地区信息
        $this->db->edit(array('area_id'=>$area_id), array('source_user_id'=>$user_id), 'wz_workflow_item'); //更新对应的评分审批地区信息        
        return array('code' =>'Success', 'msg' =>'执行成功');
    }

    //更新地区人数
    public function update_area_user_total($area_id=''){
        $area_rs = $this->db->select(array('area_id'=>$area_id), 'area_id,pid','wz_area'); //假如第三级
        if(empty($area_rs)){
            return array('code' =>'Error', 'msg' => '地区不存在');   
        }

        //更新地区各类人数
        $user_total_rs = $this->db->select(array('area_id'=>$area_id,'status'=>1,'is_villager'=>2,'is_householder'=>2), 'COUNT(*) AS count','wz_user');
        $user_type1_total_rs = $this->db->select(array('area_id'=>$area_id,'status'=>1,'is_villager'=>2,'is_householder'=>2,'type'=>1), 'COUNT(*) AS count','wz_user');//非贫困户数
        $user_type2_total_rs = $this->db->select(array('area_id'=>$area_id,'status'=>1,'is_villager'=>2,'is_householder'=>2,'type'=>2), 'COUNT(*) AS count','wz_user'); //贫困户数

        $user_total = (!empty($user_total_rs) && !empty($user_total_rs[0]['count'])) ? $user_total_rs[0]['count']: 0;
        $user_type1_total = (!empty($user_type1_total_rs) && !empty($user_type1_total_rs[0]['count'])) ? $user_type1_total_rs[0]['count']: 0;
        $user_type2_total = (!empty($user_type2_total_rs) && !empty($user_type2_total_rs[0]['count'])) ? $user_type2_total_rs[0]['count']: 0;
        $this->db->edit(array('user_total'=>$user_total,'user_type1_total'=>$user_type1_total, 'user_type2_total'=>$user_type2_total,'last_time'=>time()), array('area_id'=>$area_id), 'wz_area');


        //更新他的上级各类人数(第二级)
        if(!empty($area_rs[0]['pid'])){//有上级时（假如是第二级）
            $rs = $this->db->select(array('pid'=>$area_rs[0]['pid']), 'SUM(user_total) AS user_total, SUM(user_type1_total) AS user_type1_total, SUM(user_type2_total) AS user_type2_total','wz_area');
            $user_total = (!empty($rs) && !empty($rs[0]['user_total'])) ? $rs[0]['user_total']:0;
            $user_type1_total = (!empty($rs) && !empty($rs[0]['user_type1_total'])) ? $rs[0]['user_type1_total']:0;
            $user_type2_total = (!empty($rs) && !empty($rs[0]['user_type2_total'])) ? $rs[0]['user_type2_total']:0;
            $this->db->edit(array('user_total'=>$user_total,'user_type1_total'=>$user_type1_total, 'user_type2_total'=>$user_type2_total,'last_time'=>time()), array('area_id'=>$area_rs[0]['pid']), 'wz_area');

            $rs = $this->db->select(array('area_id'=>$area_rs[0]['pid']), 'area_id, pid','wz_area');//取第二级的上级
            if(!empty($rs[0]['pid'])){//有上级时（第一级）
                $rs = $this->db->select(array('pid'=>$rs[0]['pid']), 'area_id, pid, SUM(user_total) AS user_total, SUM(user_type1_total) AS user_type1_total, SUM(user_type2_total) AS user_type2_total','wz_area');
                $user_total = (!empty($rs) && !empty($rs[0]['user_total'])) ? $rs[0]['user_total']:0;
                $user_type1_total = (!empty($rs) && !empty($rs[0]['user_type1_total'])) ? $rs[0]['user_type1_total']:0;
                $user_type2_total = (!empty($rs) && !empty($rs[0]['user_type2_total'])) ? $rs[0]['user_type2_total']:0;
                $this->db->edit(array('user_total'=>$user_total,'user_type1_total'=>$user_type1_total, 'user_type2_total'=>$user_type2_total,'last_time'=>time()), array('area_id'=>$rs[0]['pid']), 'wz_area');
            }
        }

        return array('code' =>'Success', 'msg' =>'执行成功');
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /*
    //绑定新手机号
    public function editphone($post){
        if( empty($post['user_id']) ){
            return array('code' =>'Error', 'msg' => '用户不能为空');
        }
        if( empty($post['phone'])  || empty($post['code']) ){
            return array('code' =>'Error', 'msg' => '新手机号或验证码不能为空');
        }

        $phone = addslashes(trim($post['phone']));
        $code = addslashes(trim($post['code']));

        if(!is_phone($phone)){
            return array('code' =>'Error', 'msg' => '手机号格式不正确');
        }

        $this->sendsms_obj = $this->load_class_wz('sendsmsClass');
        $rs = $this->sendsms_obj->check(array('phone'=>$phone, 'code'=>$code));
        if($rs['code'] !='Success'){
            return $rs; //短信和验证码不对  
        }

        $user_rs = $this->db->select(array('user_id'=>$post['user_id'], 'status'=>1), 'user_id,phone', 'wz_user');
        if(empty($user_rs)){
            return array('code' =>'Error', 'msg' => '用户不存在或未启用');
        }

        $rs = $this->db->select(array('phone'=>$phone), 'user_id', 'wz_user');
        if(!empty($rs)){
            return array('code' =>'Error', 'msg' => '手机号被占用');
        }
        $rs = $this->edit(array('user_id'=>$user_rs[0]['user_id'],'phone'=>$phone));
        return $rs;
    }


    //微信登录
    public function wxlogin($post){
        //写文本日志
        $logpath='./log/wxlogin/';
        $logfile=date('Y-m-d').'_log.txt';        
        $logcontent="\r\n\r\n date('Y-m-d H:i:s').'接受数据：".json_encode($post);  
        $mode='a+'; 
        createfile($logpath,$logfile,$logcontent,$mode);
        //日志写入结束
        
        if(empty($post['weixin_openid'])){
            return array('code' =>'Error', 'msg' => 'weixin_openid不能为空');
        }
        $rs = $this->db->select(array('weixin_openid'=>$post['weixin_openid']), 'user_id', 'wz_user');

        //已存在，更新资料，即可
        if(!empty($rs)){
            $post['user_id']  = $rs[0]['user_id'];
            $this->edit($post);
            return $this->uptoken(array('user_id'=>$rs[0]['user_id']));
        }

        //不存在时
        if(empty($post['phone'])){
            return array('code' =>'Empty', 'msg' => '手机号不能为空');
        }
        if(empty($post['code'])){
            return array('code' =>'Empty', 'msg' => '验证码不能为空');
        }
        if(!is_phone($post['phone'])){
            return array('code' =>'Error', 'msg' => '手机号格式不正确');
        }
        $this->cache_obj = $this->load_class('WEBcache');
        $SQLKEY =md5('sendSMS'.$post['phone']);
        $phoneCode = $this->cache_obj->getCache($SQLKEY);//相当于取到 array('phone'=>$phone, 'code'=>$code );
        if($phoneCode['code'] != $post['code'] ){
            return array('code' =>'Error', 'msg' => '手机验证码不正确');
        }
        if($phoneCode['phone'] != $post['phone'] ){
            return array('code' =>'Error', 'msg' => '数据被非法修改');
        }

        $rs = $this->db->select(array('phone'=>$post['phone']), 'user_id', 'wz_user');

        //已存在，但手机号没有绑过微信，把资料写入即可
        if(!empty($rs)){
            $post['user_id']  = $rs[0]['user_id'];
            $this->edit($post);
            return $this->uptoken(array('user_id'=>$rs[0]['user_id']));
        }

        //不存在，则添加
        $rs = $this->add($post);
        if($rs['code']!='Success'){
            return array('code' =>'Error', 'msg' => '绑定失败，请重试');
        }

        $rs = $this->uptoken(array('user_id'=>$rs['item']));
        if($rs['code'] =='Success'){
            $this->cache_Obj->delCache(md5('sendSMS'.$phone));  //登录成功时，删除短信验证码的记录
            $this->cache_Obj->delCache('userLoginLock_'.$phone); //登录成功时，删除登录锁
            $this->db->edit(array('last_login_time'=>time()), array('user_id'=>$rs['item']['user_id']), 'wz_user');//写入最后登录时间
        }        
        return $rs;
    }

    //发送验证码
    public function get_code($post){
        $phone = addslashes(trim($post['phone']));
        if(empty($phone)){
            return array('code' =>'Error', 'msg' => '手机号不能为空');
        }
        if(!is_phone($phone)){
            return array('code' =>'Error', 'msg' => '手机号格式不正确');
        }

        if(!empty($post['is_check'])){
            $rs = $this->db->select(array('phone' =>$phone), 'user_id', 'wz_user');
            if(!empty($rs)){
                return array('code' =>'Error', 'msg' => '手机号'.$phone.'已占用');
            }
        }

        $code = rand(1000,9999);
        $phoneCode = array('phone'=>$phone, 'code'=>$code ); //把手机号和验证码，放在seesion中
        $tpl['code']=$code;
        $this->sendsms_Obj = $this->load_class_wz('sendsmsClass');
        $rs = $this->sendsms_Obj->sendSMS($phone,'86156',$tpl, $phoneCode);//注册的短信认证模块ID: 86156
        return $rs;
    }
    */

}
