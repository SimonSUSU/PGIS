<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
abstract class Db{
	protected $p_dbname;
	private $_pdo_obj;
	private $_db_config = array();
	/*
	 * Name : 构造函数
	 */
	public function __construct(){
		$this->_get_pdo_obj();
	}
	/*
	 * Name : 析构函数
	 */
	public function __destruct(){
		$this->_pdo_obj = NULL;
	}
	/*
	 * Name : 获取配置
	 */
	private function _get_db_config(){
		$db_config_arr = '';
		if(empty(self::$_db_config)){
			$db_config_arr = db_config();
		}
		if(empty($this->p_dbname)){
			$db_name_arr = array_keys($db_config_arr);	
			$this->p_dbname = $db_name_arr[0];
		}
		$this->_db_config = $db_config_arr[$this->p_dbname];
	}
	/*
	 * Name : 连接数据库
	 */
	private function _get_pdo_obj(){
		self::_get_db_config();
		try{
		    //$this->_pdo_obj = new PDO(''.$this->_db_config['db_driver'].':dbname='.$this->_db_config['db_name'].';host='.$this->_db_config['host'].'', $this->_db_config['username'], $this->_db_config['password'],array(PDO::ATTR_PERSISTENT => true));	//长链接
		    $this->_pdo_obj = new PDO(''.$this->_db_config['db_driver'].':dbname='.$this->_db_config['db_name'].';host='.$this->_db_config['host'].'', $this->_db_config['username'], $this->_db_config['password']);
		}catch (PDOException $e){
		    echo 'Connection failed: ' . $e->getMessage();exit();
		}
		$this->_pdo_obj->exec("SET NAMES ".$this->_db_config['charset']);
	}
	/*
	 * Name : 查询
	 */
	public function q_select($sql, $fetch_mode = 0){
		//echo $sql."\n";
		$result = $this->_pdo_obj->query($sql);		
		if($result){
			if(empty($fetch_mode)){
				$rs = $result->fetchAll(PDO::FETCH_ASSOC);		
			}else{
				$rs = $result->fetch(PDO::FETCH_ASSOC);
			}
		}else{
			$rs = array();
		}	
		return $rs;	
	}
	/*
	 * Name : 获取插入ID
	 */
	public function last_insert_id(){
		return $this->_pdo_obj->lastInsertId();
	}
	/*
	 * Name : 执行
	 */
	public function q_exec($sql){
		//echo $sql."\n";exit;
		return $this->_pdo_obj->exec($sql);
	}
	/*
	*Name:开启事务
	*/
	public function beginTransaction(){
		$this->_pdo_obj->beginTransaction();
	}
	/*
	*Name:提交事务
	*/
	public function commit(){
		$this->_pdo_obj->commit();
	}
	/*
	*Name:回滚事务
	*/
	public function rollBack(){
		$this->_pdo_obj->rollBack();
	}
	/*
	 * Name : 插入帮助
	 */
	public function get_sql_insert($insert_arr = array()){
		$insert_arr_t = array();
		$value_arr_t = array();
		if(is_array($insert_arr)){
			foreach($insert_arr as $key => $val){
				$insert_arr_t[] = $key;
				$value_arr_t[] = '\''.addslashes($val).'\'';
			}
			return " (".implode(',', $insert_arr_t).") values (".implode(',', $value_arr_t).")";			
		}		
	}
	/*
	 * Name : 条件帮助
	 */
	public function get_sql_cond($cond_arr = ''){
		if(!is_array($cond_arr)){
			return $cond_arr;
		}
		$cond_arr_t = array();
		foreach ($cond_arr as $key => $val){
			if(is_array($val)){
				$cond_arr_t[] = $key." in (".self::get_sql_cond_by_in($val).")";
			}else{
				$cond_arr_t[] = $key."= '".addslashes($val)."'";
			}			
		}
		return ' WHERE '.implode(' && ', $cond_arr_t);
	}
	/*
	 * Name : IN辅助
	 */
	public function get_sql_cond_by_in($cond_arr){
		$cond_arr_t = array();
		foreach ($cond_arr as $key => $val){
			$cond_arr_t[] = '\''.addslashes($val).'\'';
		}
		return implode(',', $cond_arr_t);
	}
	/*
	 * Name : 修改帮助
	 */
	public function get_sql_update($update_arr = array()){
		$update_arr_t = array();
		if(!is_array($update_arr)){
			return $update_arr;
		}
		foreach($update_arr as $key => $val){
			$update_arr_t[] = $key." = '".addslashes($val)."'";
		}
		return implode(',', $update_arr_t);
	}

	/* 解决SQL中多一个单引号 2013/3/27  */
	public function get_sql_update1($update_arr = array()){
		$update_arr_t = array();
		if(!is_array($update_arr)){
			return $update_arr;
		}
		foreach($update_arr as $key => $val){
			$update_arr_t[] = $key." = ".addslashes($val)."";
		}
		return implode(',', $update_arr_t);
	}
	/*
	 * Name : 设置主键
	 */
	public static function set_index($arr, $key){
		if(empty($arr))return array();
		$temp = array();
		foreach($arr as $val){
			if (!isset($val[$key])){
				return $arr;
			}
			$temp[$val[$key]] = $val;
		}
		return $temp;
	}
	/*
	 * Name : 排序帮助
	 */
	public static function sort($sort){
		$sort_arr = array();
		if(is_array($sort)){
			foreach($sort as $key => $val){
				$sort_arr[] = $key.' '.$val;
			}
			return ' ORDER BY '.implode(',', $sort_arr);
		}else{
			return $sort;
		}
	}
	
	
}
?>