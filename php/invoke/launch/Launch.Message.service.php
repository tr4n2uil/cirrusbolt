<?php 
require_once(SBSERVICE);

/**
 *	@class LaunchMessageService
 *	@desc Launches workflows from messages
 *
 *	@param message array Message to be launched [memory]
 *	@param uiconf array UI data [memory] optional default false
 *
 *	@return response array Output parameters for service execution [memory]
 *	@param ui array UI data [memory] optional default false
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
			'required' => array('message'),
			'optional' => array('uiconf' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['response'] = array();
		$memory['ui'] = array();
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
			
			list($root, $service, $operation) = explode('.' ,$uri);
			if($memory['uiconf'] && isset($memory['uiconf'][$root.'.'.$service.'.'.$operation])){
				$memory['ui'] = $memory['uiconf'][$root.'.'.$service.'.'.$operation]['uidata'];
			}
		}
		
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('response', 'ui', 'valid', 'msg', 'status', 'details');
	}
	
}

?>