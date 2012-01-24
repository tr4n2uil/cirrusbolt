<?php 
require_once(SBSERVICE);
require_once(CACHELITE);

/**
 *	@class LiteGetService
 *	@desc Gets Data from Cache using CacheLite
 *
 *	@param key string Key [memory]
 *	@param cachelite array CacheLite configuration [Snowblozm] (caching, cacheDir, lifeTime, automaticCleaningFactor, hashedDirectoryLevel)
 *
 *	@return data string Cached data [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class LiteGetService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('key')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$opitons = Snowblozm::get('cachelite');
		
		$cache = new Cache_Lite($options);
		$data = $cache->get($memory['key']);
		
		if($data === false){
			$memory['valid'] = false;
			$memory['msg'] = 'Error Getting Data';
			$memory['status'] = 200;
			$memory['details'] = 'Error getting data from cache @pool.lite.get service';
			return $memory;
		}
		
		$memory['data'] = $data;
		$memory['valid'] = true;
		$memory['msg'] = 'Data Given Successfully';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('data');
	}
	
}

?>