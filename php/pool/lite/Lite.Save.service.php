<?php 
require_once(SBSERVICE);
require_once(CACHELITE);

/**
 *	@class LiteSaveService
 *	@desc Saves Data to Cache using CacheLite
 *
 *	@param key string Key [memory]
 *	@param data string Data [memory] 
 *	@param expiry long int Expiry time [memory] optional default false
 *	@param cachelite array CacheLite configuration [Snowblozm] (caching, cacheDir, lifeTime, automaticCleaningFactor, hashedDirectoryLevel)
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class LiteSaveService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('key', 'data'),
			'optional' => array('expiry' => false)
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$opitons = Snowblozm::get('cachelite');
		
		$life = $memory['expiry'] ? array('lifeTime' => $memory['expiry']) : array();
		$cache = new Cache_Lite(array_merge($options, $life));
		
		if(!$cache->save($memory['data'], $memory['key'])){
			$memory['valid'] = false;
			$memory['msg'] = 'Error Caching Data';
			$memory['status'] = 200;
			$memory['details'] = 'Error caching data @pool.lite.save service';
			return $memory;
		}
		
		$memory['valid'] = true;
		$memory['msg'] = 'Data Cached Successfully';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>