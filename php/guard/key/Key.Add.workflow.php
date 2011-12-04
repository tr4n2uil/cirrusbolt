<?php 
require_once(SBSERVICE);

/**
 *	@class KeyAddWorkflow
 *	@desc Adds new service key
 *
 *	@param email string Email [memory]
 *	@param key string Key value [memory]
 *
 *	@return return id long int Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class KeyAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('key', 'email')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Key created successfully';
		
		$service = array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('key', 'email'),
			'conn' => 'adconn',
			'relation' => '`keys`',
			'sqlcnd' => "(`email`, `keyvalue`) values ('\${email}', MD5('\${email}\${key}'))",
			'escparam' => array('key', 'email')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>