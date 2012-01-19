<?php 
require_once(SBSERVICE);

/**
 *	@class LaunchMessageService
 *	@desc Launches workflows from messages
 *
 *	@param message array Message to be launched [memory]
 *
 *	@return response array Output parameters for service execution [memory]
 *	@return valid boolean Service execution validity [memory]
 *	@return msg string Service execution result [memory]
 *	@return status integer Service execution status [memory]
 *	@return details string Service execution details [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class LaunchMessageService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('message')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['response'] = array();
		$message = $memory['message'];
		$uri = $message['service'];
		
		/**
		 *	Run the service
		**/
		$memory = Snowblozm::run($message, $memory);
		
		/**
		 *	Read service output
		**/
		if($memory['valid']){
			$instance = Snowblozm::load($uri);
			foreach($instance->output() as $key){
				$memory['response'][$key] = $memory[$key];
			}
		}
		
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('response', 'valid', 'msg', 'status', 'details');
	}
	
}

?>