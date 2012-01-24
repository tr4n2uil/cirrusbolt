<?php 
require_once(SBSERVICE);

/**
 *	@class LiteGetWorkflow
 *	@desc Gets Data from Cache using CacheLite after decoding
 *
 *	@param key string Key [memory]
 *	@param type string Request type [memory] optional default 'json' ('json', 'xml', 'wddx')
 *	@param cachelite array CacheLite configuration [Snowblozm] (caching, cacheDir, lifeTime, automaticCleaningFactor, hashedDirectoryLevel)
 *
 *	@return data string Cached data [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class LiteGetWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('key'),
			'optional' => array('type' => 'json')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Data Given Successfully';
		
		$workflow = array(
		array(
			'service' => 'pool.lite.save.service'
		),
		array(
			'service' => 'cbcore.data.decode.service',
			'input' => array('data' => 'result'),
			'output' => array('result' => 'data')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('data');
	}
	
}

?>