<?php 
require_once(SBSERVICE);

/**
 *	@class LaunchMessageService
 *	@desc Launches workflows from messages
 *
 *	@param message array Message to be launched [memory]
 *	@param uiconf array UI data [memory] optional default false
 *
 *	@return message array Output parameters for service execution [memory]
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
		
		/**
		 *	Check for invalid service check
		**/
		if(isset($message['valid'])){
			$memory['valid'] = false;
			$memory['msg'] = $message['msg'];
			$memory['status'] = $message['status'];
			$memory['details'] = $message['details'];
			return $memory;
		}
		
		/**
		 *	Get service URI
		**/
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
		if($memory['uiconf'] && file_exists($memory['uiconf'].$root.'/'.$service.'/uidata.php') && $uidata = include($memory['uiconf'].$root.'/'.$service.'/uidata.php')){
			$message['ui'] = isset($uidata[$operation]) ? $uidata[$operation] : array();
		}
		
		$memory['message'] = $message;
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
		return array('message');
	}
	
}

?>