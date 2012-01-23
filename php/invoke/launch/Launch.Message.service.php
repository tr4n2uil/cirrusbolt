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
		$message = $memory['message'];
		$uri = $message['service'];
		
		/**
		 *	Run the service
		**/
		$message = Snowblozm::run(array(
			'service' => $uri
		), $message);
		
		/**
		 *	Set UI data
		**/
		list($root, $service, $operation) = explode('.' ,$uri);
		if($memory['uiconf'] && isset($memory['uiconf'][$root.'.'.$service.'.'.$operation])){
			$message['ui'] = $memory['uiconf'][$root.'.'.$service.'.'.$operation]['uidata'];
		}
		
		$memory['response'] = $message;
		$memory['valid'] = true;
		$memory['msg'] = 'Launched Successfully';
		$memory['status'] = 200;
		$memory['details'] = "Successfully executed";
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('response');
	}
	
}

?>