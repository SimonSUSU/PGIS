<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Db_pdo extends Db{
	protected $p_table_name;
	private static $_instance;
	
	
	public static function get_instance(){
		if (!isset(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}	
	
	public function select($cond_arr=array(), $field='*', $tb_name = 0,  $index = 0, $limit = '', $sort='',$fetch = 0,$issql=0){
		$tb_name = empty($tb_name) ? 0 : $tb_name;
		$limit_str = !is_array($limit) ? $limit : ' limit '.$limit[0].','.$limit[1].'';
		$sort_str = $this->sort($sort);
		$sql = "SELECT ".$field." FROM ".$tb_name.$this->get_sql_cond($cond_arr).$sort_str.$limit_str."";
		if($issql==1){
			return $sql;
		}
		$mch = $this->WEBcacheDIY();
		$SQLKEY =md5($sql.$index);
		$result = $mch->getCache($SQLKEY);
		if($result){
			$key_time = $mch->getCache($SQLKEY.'_TIME');
			$table_time = $mch->getCache($tb_name.'_TIME');
			if ($key_time > $table_time || (empty($table_time) && !empty($key_time))){
				return $result;
			}
		}

		if(empty($index)){
			$result = $this->q_select($sql);
		}else{
			$result = $this->set_index($this->q_select($sql), $index);
		}
		$mch->setCache($SQLKEY, $result);
		$mch->setCache($SQLKEY.'_TIME',time());
		return $result;		
	}

	
	public function insert($insert_arr = array(), $tb_name = 0,$issql=0){
		$value_str = parent::get_sql_insert($insert_arr);
		$sql = "INSERT INTO ".$tb_name.$value_str."";
		if($issql==1){
			return $sql;
		}
		$this->q_exec($sql);		
		$mch = $this->WEBcacheDIY();
		$mch->setCache($tb_name.'_TIME', time() );
		return $this->last_insert_id();
	}
	
	public function edit($update_arr = array(), $cond_arr = array(), $tb_name = 0, $issql=0){
		$update_str = parent::get_sql_update($update_arr);
		$cond_str = parent::get_sql_cond($cond_arr);
		$sql = "UPDATE ".$tb_name." SET ".$update_str.$cond_str."";
		if($issql==1){
			return $sql;
		}
		$mch = $this->WEBcacheDIY();
		$mch->setCache($tb_name.'_TIME', time() );
		return $this->q_exec($sql);
	}

	public function del($cond_arr = array(), $tb_name = 0, $issql=0){
		$sql = "DELETE FROM ".$tb_name.parent::get_sql_cond($cond_arr)."";
		if($issql==1){
			return $sql;
		}
		$mch = $this->WEBcacheDIY();
		$mch->setCache($tb_name.'_TIME', time() );
		return $this->q_exec($sql);
	}

	public function sexec($sql, $tb_name = array(), $type = ''){
		if(!is_array($tb_name)){
			echo 'tb_name必须是数组';exit;
		}
		if(empty($type)){
			echo 'type 不能为空';exit;
		}
		$type = strtolower($type);
		$mch = $this->WEBcacheDIY();	
		$SQLKEY =md5($sql);

		if($type=='select'){
			$verify = true;
			$key_time = $mch->getCache($SQLKEY.'_TIME');
			foreach ($tb_name as $k => $v){
				$table_time = $mch->getCache($v.'_TIME');
				if( $key_time < $table_time || empty($key_time) ){
					$verify = false;
					break;
				}
			}		
			if($verify){
				$result = $mch->getCache($SQLKEY);
				if(!empty($result)){
					return $result;
				}
			}
		}

		$result = $this->q_select($sql);
		if($type=='select'){
			$mch->setCache($SQLKEY, $result);
			$mch->setCache($SQLKEY.'_TIME',time());
			$a = $mch->getCache($SQLKEY);
		}else{
			foreach ($tb_name as $k => $v){
				$mch->setCache($v.'_TIME',time());
			}
		}
		return $result;
	}
	
	public function __destruct(){
		parent::__destruct();
	}


	public function WEBcacheDIY(){
		require_once("lib/classes/WEBcache.php");
		$cache = new WEBcache();
		return $cache;
	}

}
?>