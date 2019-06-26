<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class WEBcache {
	protected $memcache;

	public function memcache(){
		if( extension_loaded('Memcache')==1){//判断模块是否开启
			$memcache = new Memcache;
			$mc_config = memcache_config();
			foreach ($mc_config as $k => $v){
				$memcache->addServer($v['host'], $v['port'],true);
				$memcache->setCompressThreshold(20000, 0.2);//大于 20000字节的进行压缩，压缩率为20%
				//$memcache->pconnect($v['host'], $v['port']);
			}
		}
		return $memcache;
	}

	//获取缓存
	public function getCache($key){
		if( extension_loaded('Memcache')==1 ){//判断模块是否开启
			$site_config = site_config();
			$web_key = $site_config['url_key'];
			$key = md5($web_key.$key);
			$memcache = $this->memcache();
			$data= $memcache->get($key);			
			return $data;
		}else{
			return false;
		}
	}
	
	//生成缓存
	public function setCache($key='', $var='', $flag =false, $expire = 86400){//缓存有效，默认为1天
		if( extension_loaded('Memcache')==1 ){//判断模块是否开启
			$site_config = site_config();
			$web_key = $site_config['url_key'];
			$key = md5($web_key.$key);
			$memcache = $this->memcache();
			$memcache->set($key, $var, $flag, $expire);
			$memcache->set($key . '_TIME', time(), $flag, $expire);
		}
	}

	//删除缓存
	public function delCache($key){
		if( extension_loaded('Memcache')==1 ){//判断模块是否开启
			$site_config = site_config();
			$web_key = $site_config['url_key'];
			$key = md5($web_key.$key);
			$memcache = $this->memcache();
			$memcache->delete($key);
			$memcache->delete($key . '_TIME');
		}
	}

	//清除所有缓存
	public function flushCache(){
		$memcache = $this->memcache();
		if( extension_loaded('Memcache')==1){//判断模块是否开启
			$memcache_config = memcache_config();
			foreach ($memcache_config as $k => $v){
				$memcache->connect($v['host'],$v['port']);
				$memcache->flush();
			}
		}		
	}
}
?>