<?php 
require_once(SBSERVICE);

/**
 *	@class RequestReadService
 *	@desc Reads HTTP request from input stream
 *
 *	@return data string Stream data [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class RequestReadService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array();
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['data'] = file_get_contents('php://input');
		
		$memory['valid'] = true;
		$memory['msg'] = 'Valid Request';
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