<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 标签管理 */
class tagClass extends Controllers{
	function __construct($dir = ''){
        parent::__construct();
	}

    //列表
    public function lists($post, $field='*'){
        $keyword = !empty($post['keyword'])? addslashes(trim($post['keyword'])) :'';
        $pageSize = empty($post['pagesize']) ? $this->_page : $post['pagesize'];
        $p = empty($post['page']) ? 0 : $post['page'];
        $offset = ($p <= 0) ? 0 : ($p - 1) * $pageSize;

        $where = '';
        if (!empty($post['status'])){
            $where .= ' AND status='.$post['status'];
        }
        if (!empty($post['start_date'])){
            $where.=' AND add_time>='.strtotime($post['start_date'].' 00:00:00');
        }
        if (!empty($post['end_date'])){
            $where.=' AND add_time<='.strtotime($post['end_date'].' 23:59:59');
        }
        if (!empty($keyword)){
            $where .= " AND name LIKE '%" . $keyword ."%' ";
        }

        $where_count = $where;
        $where .=' ORDER BY sorting DESC , tag_id DESC ';
        $rs = $this->db->select(' WHERE 1=1 ', $field, 'wz_tag', 0, array($offset, $pageSize), $where);
        if(empty($rs)){
            return array('code' =>'Empty', 'msg' => '没有符合条件的记录');
        }
        foreach ($rs as $k => $v){
            if(isset($v['status'])){
                $rs[$k]['status_str'] = $this->status_arr[$v['status']];
            }
            if(isset($v['pic']) && !empty($v['pic'])){
                if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
                   $rs[$k]['pic'] = $this->img($v['pic'],$post['imgWidth'],$post['imgHeight']);
                }else{
                    $rs[$k]['pic'] = $this->img($v['pic'],0,0);
                }
            }          
            if(isset($v['add_time'])){
                $rs[$k]['add_time_str'] = date('Y-m-d H:i:s',$v['add_time']);
            }
        	if(isset($v['last_time'])){
        		$rs[$k]['last_time_str'] = date('Y-m-d H:i:s',$v['last_time']);
        	}
        }

        $rs_toall = $this->db->select(' WHERE 1=1 ', 'COUNT(*) AS count','wz_tag',0,'',$where_count);
        $page['toall'] = $rs_toall[0]['count'];
        $page['total_page'] = ceil($rs_toall[0]['count'] / $pageSize);
        $page['pageSize'] = $pageSize;
        $page['page'] = $p;
        return array('code' =>'Success', 'item' => $rs, 'page'=>$page );
    }

    //详细
    public function view($post, $field='*'){
        if(empty($post['tag_id'])){
            return array('code' =>'Error', 'msg' => 'ID不能为空');
        }
        $rs = $this->db->select(array('tag_id' =>$post['tag_id']), $field, 'wz_tag');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '不存在');
        }
        if(isset($rs[0]['pic']) && !empty($rs[0]['pic'])){
            if( !empty($post['imgWidth']) || !empty($post['imgHeight']) ){
               $rs[0]['pic'] = $this->img($rs[0]['pic'],$post['imgWidth'],$post['imgHeight']);
            }else{
                $rs[0]['pic'] = $this->img($rs[0]['pic'],0,0);
            }
        }
        if(isset($rs[0]['status'])){
            $rs[0]['status_str'] = $this->status_arr[$rs[0]['status']];
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
        if(empty($post['name'])){
            return array('code' =>'Error', 'msg' => 'name不能为空');
        }
        $field_rs = $this->field($post,$files);//组装各字段
        if($field_rs['code'] !='Success'){
            return $field_rs;
        }
        $data = $field_rs['item'];
        $data['add_time'] = time();
        $data['last_time'] = time();
        $rs = $this->db->insert($data,'wz_tag');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '添加失败');
        }
        return array('code' =>'Success', 'item' => $rs);
    }


    //修改
    public function edit($post,$files=''){
        if(empty($post['tag_id'])){
            return array('code' =>'Error', 'msg' => '用户ID不能为空');
        }

        $rs = $this->db->select(array('tag_id' =>$post['tag_id']), 'pic', 'wz_tag');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '不存在');
        }

        $field_rs = $this->field($post,$files,$rs[0]);//组装各字段
        if($field_rs['code'] !='Success'){
            return $field_rs;
        }        
        $data = $field_rs['item'];
        if(empty($data)){
            return array('code' =>'Error', 'msg' => '修改项不能为空');
        }

        $data['last_time'] = time();
        $edit_rs = $this->db->edit($data, array('tag_id'=>$post['tag_id']), 'wz_tag');
        if(empty($edit_rs)){
            return array('code' =>'Error', 'msg' => '无修改');
        }

        if(!empty($data['pic'])){
            $this->diy_delFile($rs[0]['pic']);
        }

        return array('code' =>'Success', 'msg' => '修改成功');
    }

    //添加、修改的字段项
    public function field($post, $files='',$rs=''){
        $data = array();        
        if(isset($post['name'])){
            if(empty($post['name'])){
                return array('code' =>'Error', 'msg' => '名称不能为空');
            }
            $name = filterEmoji($post['name']);
            $name = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$name);
            if(mb_strlen($name,'utf8') <1 || mb_strlen($name,'utf8') >200){
                return array('code' =>'Error', 'msg' => '名称最少1个字，最多200个字');
            }
            $data['name'] = $name;
        }

        if(isset($post['sorting'])){
            $data['sorting'] = $post['sorting'];
        }
        if(isset($post['status'])){
            $data['status'] = $post['status'];
        }        
        if(!empty($files)){//当上传的图片不为空时，
            $uploadObj = $this->load_class('upload');

            if( !empty($files['pic']['tmp_name']) ){
                $result = $uploadObj->upload_file($files['pic'],'tagpic','pic');
                if(!empty($result['code']) && $result['code']=='Error'){
                    return $result; //上传错时
                }
                if(!empty($result)){
                    $data['pic'] = $result;
                }
            }
        }
        return array('code' =>'Success', 'item' =>$data);
    }

    //删除
    public function del($post){
        if(empty($post['tag_id'])){
            return array('code' =>'Error', 'msg' => 'tag_id不能为空');
        }
        $rs = $this->db->select(array('tag_id' =>$post['tag_id']), 'pic', 'wz_tag');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '不存在');
        }

        $del_rs = $this->db->del(array('tag_id'=>$post['tag_id']), 'wz_tag');
        if(empty($del_rs)){
            return array('code' =>'Error', 'msg' => '删除失败');
        }

        if(!empty($rs[0]['pic'])){
            $this->diy_delFile($rs[0]['pic']);
        }

        return array('code' =>'Success', 'item' => $del_rs);
    }

}
