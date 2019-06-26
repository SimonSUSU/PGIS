<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* remark : 权限节点和权限组相关 */
class purviewClass extends Controllers{
	function __construct($dir = ''){
        $this->db  = Db_pdo::get_instance();
	}

    //列表
    public function lists($post, $field='*'){
        $where = '';
        if( !empty($post['is_system']) ){
            $where.=' AND is_system='.$post['is_system'];
        }
        $where.=' ORDER BY sorting DESC, purview_id DESC ';
		$rs = $this->db->select(' where 1=1 ','*','wz_purview','purview_id','', $where);
        if(empty($rs)){
            return array('code' =>'Empty', 'msg' => '没有节点');
        }
        foreach ($rs as $k => $v){
            $rs[$k]['is_system_str'] = ($v['is_system']==1)?'是':'否';
            $rs[$k]['status_str'] = ($v['status']==1)?'是':'否';
        }
        return array('code' =>'Success', 'item' => $rs);
	}

	//详细
    public function view($post){
        if(!is_numeric($post['purview_id'])){
            return array('code' =>'Error', 'msg' => 'purview_id不能为空');
        }
        $rs = $this->db->select(array('purview_id' =>$post['purview_id']), '*', 'wz_purview');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '读取失败');
        }

        $rs[0]['is_system_str'] = ($rs[0]['is_system']==1)?'是':'否';
        $rs[0]['status_str'] = ($rs[0]['status']==1)?'是':'否';
        return array('code' =>'Success', 'item' => $rs[0]);
    }

	//添加
    public function add($post){
        if(empty($post['name'])){
            return array('code' =>'Error', 'msg' => '名称不能为空');
        }
        $name =  trim(filterEmoji($post['name']));
        //$name = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$name);
        if(mb_strlen($name,'utf8') <1 || mb_strlen($name,'utf8') >100 ){
            return array('code' =>'Error', 'msg' => '名称最少1个字，最多100个字');
        }   
        $data = array(
            'name'  =>$name,
        );
        if(!empty($post['url'])){
            $data['url'] = trim($post['url']);
        }
        if(!empty($post['pid'])){
            $data['pid'] = $post['pid'];
        }
        if(!empty($post['sorting'])){
            $data['sorting'] = $post['sorting'];
        }
        if(!empty($post['is_system'])){
            $data['is_system'] =1;
        }else{
            $data['is_system'] =2;
        }
        if(!empty($post['status'])){
            $data['status'] =1;
        }else{
            $data['status'] =2;
        }
        $rs = $this->db->insert($data,'wz_purview');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '添加失败');
        }
    }

    //修改
    public function edit($post){
        if(empty($post['purview_id'])){
            return array('code' =>'Error', 'msg' => 'purview_id不能为空');
        }
        if(empty($post['name'])){
            return array('code' =>'Error', 'msg' => '名称不能为空');
        }
        if($post['purview_id']==$post['pid']){
        	 return array('code' =>'Error', 'msg' => '上级节点不能为节点本身！');
		}else{
			$rs = $this->lists(array());
			$returnNodeTree = $this->scanNodeOfTree($rs['item'], $post['purview_id']);
			if(in_array($post['pid'], $returnNodeTree)){
				return array('code' =>'Error', 'msg' => '上级节点不能为节点的子级！');
			}		
		}

        $name =  trim(filterEmoji($post['name']));
        //$name = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$name);
        if(mb_strlen($name,'utf8') <1 || mb_strlen($name,'utf8') >100 ){
            return array('code' =>'Error', 'msg' => '名称最少2个字，最多100个字');
        }

        $data = array(
            'name'  =>$name,
        );
        if(!empty($post['url'])){
            $data['url'] = trim($post['url']);
        }
        if(isset($post['pid'])){
            $data['pid'] = $post['pid'];
        }
        if(isset($post['sorting'])){
            $data['sorting'] = $post['sorting'];
        }
        if(!empty($post['is_system'])){
            $data['is_system'] =1;
        }else{
            $data['is_system'] =2;
        }
        if(!empty($post['status'])){
            $data['status'] =1;
        }else{
            $data['status'] =2;
        }
        $rs = $this->db->edit($data, array('purview_id'=>$post['purview_id']), 'wz_purview');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '内容无修改');
        }
    }

    //删除
    public function del($post){
        if(empty($post['purview_id'])){
            return array('code' =>'Error', 'msg' => 'purview_id不能为空');
        }
        $rs = $this->lists(array());
        $returnNodeTree = $this->scanNodeOfTree($rs['item'],$post['purview_id']);
        if(!empty($returnNodeTree)){
            return array('code' =>'Error', 'msg' => '本节点有下级节点，不可删除！');
        }
        $rs = $this->db->del(array('purview_id'=>$post['purview_id']), 'wz_purview');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '删除失败');
        }
    }

    //返回所有的叶子节点，排除父子死循环；
	public function scanNodeOfTree($result,$pid=0,&$array=array()){
		static $i=0;
		if((bool)$result){
			foreach($result as $v){
				if($v['pid']==$pid){
					$array[$i]=$v['purview_id'];
					$i++;
					$this->scanNodeOfTree($result,$v['purview_id'],$array);
				}
			}
		}
		return $array;
	}

	//节点的树
	public function tree($rs, $pid = 0, $str = '', $isSelect = 0, $pidSelect = 0, $idarrey=''){
		$treeStr = '';
		foreach ($rs as $k => $v) {
			if($pid == $v['pid']){
				if(empty($isSelect)){
					$temparr = explode("_",$idarrey);
					$temparrNum = count($temparr,COUNT_NORMAL)-1;
					if($temparr[$temparrNum]!=$v['pid']){
						if($idarrey!=""){$idarrey=$idarrey.'_'.$v['pid'];}
						else{
							if($v['pid']!=0){$idarrey=$idarrey.$v['pid'];}
						}
						$temparr = explode("_",$idarrey);
						$temparrNum = count($temparr,COUNT_NORMAL)-1;
					}
						
					$pdleft=0;
					$paddingleft=' style="padding-left:';
					$idstr=' class="'.'tree_'.$idarrey;
					$idbntstr='';
					for ($i=0; $i<=$temparrNum; $i++) {
						$pdleft+=2;
						$idstr.=' te'.'_'.$temparr[$i];
						$idbntstr.=' bntte_'.$temparr[$i].'';
					}
					if($idarrey==''){$pdleft-=2;}
					$paddingleft=$paddingleft.$pdleft.'em;"';
					$idstr.='"';

					if($v['is_system']==1){
						$is_system='<strong style="color:#f20">是</strong>';
					}else{
						$is_system='否';
					}
					if($v['status']==1){
						$status='<strong style="color:#f20">是</strong>';
					}else{
						$status='否';
					}

					$treeStr .= "<tr".$idstr.">
					<td>".$v['purview_id']."</td>
					<td class=\"tdLeft\"".$paddingleft."><span class=\"treespread".$idbntstr."\" onclick=\"javascript:TreeSpread(this,".$v['purview_id'].");\">-</span> ".$v['name']."</td>
					<td class=\"tdLeft\">".$v['url']."</td>	
					<td>".$v['sorting']."</td>
					<td>".$is_system."</td>
					<td>".$status."</td>
					<td width=\"100\"><a href=\"javascript:;\" onclick=\"openWin('".url(array('purview', 'add', $v['purview_id']))."?path=".$v['url']."', '600px','320px','添加节点');\">新增下级</a></td>
					<td width=\"50\"><a href=\"javascript:;\" onclick=\"openWin('".url(array('purview', 'edit', $v['purview_id']))."', '600px','320px','修改节点');\">修改</a></td>
					<td width=\"50\"><a href='".url(array('purview', 'del', $v['purview_id']))."' onclick=\"return confirm('是否删除?')\">删除</a></td>
					</tr>\n";



				}else{
					$selectStr = ($v['purview_id'] == $pidSelect) ? 'selected' : '';
					$treeStr .= "<option value='".$v['purview_id']."' ".$selectStr.">".$str.$v['name']."（".$v['url']."）</option>\n";
				}
				$treeStr .= self::tree($rs, $v['purview_id'], $str.' - ', $isSelect, $pidSelect, $idarrey);
			}
		}
		return $treeStr;
	}


    //权限组列表
	public function group_lists($post, $field='*'){
		$keyword = !empty($post['keyword']) ? addslashes(trim($post['keyword'])) : '';
        $pageSize = empty($post['pagesize']) ? $this->_page : $post['pagesize'];
        $p = empty($post['page']) ? 0 : $post['page'];
        $offset = ($p <= 0) ? 0 : ($p - 1) * $pageSize;

        $where = '';
        if (!empty($post['status'])){
            $where .= ' AND status = '.$post['status'];
        }
        if (!empty($keyword)){
            $where .= " AND name like '%".$keyword."%' ";
        }
        $where_count = $where;
        $where .= " ORDER BY sorting DESC, purviewgroup_id DESC "; 
        $rs = $this->db->select(' WHERE 1=1 ', $field, 'wz_purviewgroup', 0, array($offset, $pageSize), $where);
        if(empty($rs)){
            return array('code' =>'Empty', 'msg' => '没有权限组');
        }
        foreach ($rs as $k => $v){
            if(isset($v['status'])){
                $rs[$k]['status_str'] = $this->status_arr[$v['status']].'登录';
            }
            if(isset($v['add_time'])){
                $rs[$k]['add_time_str'] = date('Y-m-d H:i:s',$v['add_time']);
            }
            if(isset($v['last_time'])){
                $rs[$k]['last_time_str'] = date('Y-m-d H:i:s',$v['last_time']);
            }
        }
        $rs_toall = $this->db->select(' WHERE 1=1 ', 'COUNT(*) AS count','wz_purviewgroup',0,'',$where_count);
        $page['toall'] = $rs_toall[0]['count'];
        $page['total_page'] = ceil($rs_toall[0]['count'] / $pageSize);
        $page['pageSize'] = $pageSize;
        $page['page'] = $p;

        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs, 'page'=>$page );
        }else{
            return array('code' =>'Empty', 'msg' => '没有权限组');
        }
    }

    //权限组查看
    public function group_view($post, $field='*'){
        if(empty($post['purviewgroup_id'])){
            return array('code' =>'Error', 'msg' => 'purviewgroup_id不能为空');
        }
        $rs = $this->db->select(array('purviewgroup_id'=>$post['purviewgroup_id']), $field, 'wz_purviewgroup');
        if(!empty($rs)){
            return array('code' =>'Success', 'post'=>$post, 'item' => $rs[0]);
        }else{
            return array('code' =>'Error', 'msg' => '读取失败');
        }
    }

    //权限组添加
    public function group_add($post){
    	if(empty($post['name'])){
            return array('code' =>'Error', 'msg' => '名称不能为空');
        }
        $data = array(
            'add_time'  =>time(),
            'last_time'  =>time(),
        );

        $name =  trim(filterEmoji($post['name']));
        $name = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$name);
        if(mb_strlen($name,'utf8') <1 || mb_strlen($name,'utf8') >20 ){
            return array('code' =>'Error', 'msg' =>'名称最少1个字，最多20个字');
        }
        $data['name'] = $name;

        if(!empty($post['sorting'])){
            $data['sorting'] = $post['sorting'];
        }
        if(!empty($post['purview'])){
            $data['purview'] = $post['purview'];
        }
        if(!empty($post['remark'])){
            $data['remark'] = $post['remark'];
        }
        if(!empty($post['status'])){
            $data['status'] = $post['status'];
        }
        $rs = $this->db->insert($data,'wz_purviewgroup');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '添加失败');
        }
    }


    //权限组修改
    public function group_edit($post){
        if(empty($post['purviewgroup_id'])){
            return array('code' =>'Error', 'msg' => 'ID不能为空');
        }
        $data = array();
        if(!empty($post['name'])){
            $name =  trim(filterEmoji($post['name']));
            $name = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$name);
            if(mb_strlen($name,'utf8') <1 || mb_strlen($name,'utf8') >20 ){
                return array('code' =>'Error', 'msg' =>'名称最少1个字，最多20个字');
            }
            $data['name'] = $name;
        }

        if(isset($post['sorting'])){
            $data['sorting'] = $post['sorting'];
        }
        if(isset($post['purview'])){
            $data['purview'] = $post['purview'];
        }
        if(!empty($post['remark'])){
            $data['remark'] = $post['remark'];
        }
        if(!empty($post['status'])){
            $data['status'] = $post['status'];
        }
        if(empty($data)){
            return array('code' =>'Error', 'msg' =>'修改项不能为空');
        }
        $data['last_time'] = time();
        $rs = $this->db->edit($data, array('purviewgroup_id'=>$post['purviewgroup_id']), 'wz_purviewgroup');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '内容无修改');
        }
    }

    //权限组删除
    public function group_del($post){
        if(empty($post['purviewgroup_id'])){
            return array('code' =>'Error', 'msg' => 'purviewgroup_id不能为空');
        }
        $rs = $this->db->select(array('purviewgroup_id'=>$post['purviewgroup_id']), 'purviewgroup_id', 'wz_purviewgroup');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '权限组不存在');
        }

        $rs = $this->db->select(array('purviewgroup_id' =>$post['purviewgroup_id']), 'user_id', 'wz_user',0, array(0,1));
        if(!empty($rs)){
            return array('code' =>'Error', 'msg' => '此权限组下还有管理员，不可删除');
        }
        $rs = $this->db->del(array('purviewgroup_id'=>$post['purviewgroup_id']), 'wz_purviewgroup');
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $rs);
        }else{
            return array('code' =>'Error', 'msg' => '删除失败');
        }
    }

    

    //设置状态
    function groupSetStatus($post){
        if(empty($post['purviewgroup_id'])){
            return array('code' =>'Error', 'msg' => 'purviewgroup_id不能为空');
        }

        $rs = $this->db->select(array('purviewgroup_id'=>$post['purviewgroup_id']), 'purviewgroup_id,status', 'wz_purviewgroup');
        if(empty($rs)){
            return array('code' =>'Error', 'msg' => '信息不存在');
        }

        if($rs[0]['status']==1){
            $rs = $this->db->edit(array('status'=>2), array('purviewgroup_id'=>$post['purviewgroup_id']), 'wz_purviewgroup');
            $info ='禁用';
        }else{
            $rs = $this->db->edit(array('status'=>1), array('purviewgroup_id'=>$post['purviewgroup_id']), 'wz_purviewgroup');
            $info ='启用';
        }
        if(!empty($rs)){
            return array('code' =>'Success', 'item' => $info);
        }else{
            return array('code' =>'Error', 'msg' => '设置失败');
        }
    }


    //传递所在的权限组ID，算出用户的权限节点URL的数组
    //用户有权限的节点数组 = （对应的权限组节点 + 系统级节点）-->合并 -->将有权限的所有节点，再查出这些节点的下级和下下级是继承的
    public function userAuth($post){
        $all_rs = $this->db->select(' WHERE 1=1 ', '*', 'wz_purview','purview_id'); //所有节点
        $user_rs_temp = $this->db->select(array('purviewgroup_id'=>$post['purviewgroup_id'],'status'=>1), '*', 'wz_purviewgroup');//权限组权限节点ID
        if(empty($user_rs_temp)){
            return array('code'=>'Error','msg'=>'没有权限组或不可用');
        }
        $user_rs_temp = $user_rs_temp[0]['purview'];
        $user_rs_temp = explode('|',$user_rs_temp);
        $user_rs = array();
        foreach ($user_rs_temp as $k => $v) {//格式转换
             $user_rs[$v]=$v;
        }

        $user_auth_rs = array();//用户有权限的数组
        foreach ($all_rs as $k => $v){
            if($v['is_system']==1){//系统级的权限节点
                $user_auth_rs[$v['purview_id']] = $v;
            }

            if(!empty($user_rs[$v['purview_id']]) ){//用户权限组的节点(判断方式，所有节点在权限组中的ID，则取出来 )
                $user_auth_rs[$v['purview_id']] = $v;
            }

            if($v['status'] ==1 ){//查出他的下级是继承他的，合并到$auth_rs
                $user_auth_rs[$k] = $v;
            }
        }
        //print_r($user_auth_rs); exit;
        $auth_arr = array();
        foreach ($user_auth_rs as $k => $v){
            $auth_arr[] =$v['url'];
        }
        //print_r($auth_arr);exit;
        return array('code'=>'Success', 'item'=>$auth_arr);
    }
	
}
?>