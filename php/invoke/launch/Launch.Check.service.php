<?php 
require_once(SBSERVICE);

/**
 *	@class LaunchCheckService
 *	@desc Checks workflows before launch
 *
 *	@param message array Message to be launched [memory]
 *	@param access array Array of allowed values for controlling workflows executed [memory] optional default array()
 *
 *	@return message array Message to be launched [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class LaunchCheckService implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('message'),
			'optional' => array('access' => array())
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$message = $memory['message'];
		$access = $memory['access'];
		
		/**
		 *	Check for valid service request
		**/
		if(!isset($message['service'])){
			$memory['valid'] = false;
			$memory['msg'] = 'Invalid Message';
			$memory['status'] = 500;
			$memory['details'] = 'Please specify service to be executed with param service=root.service.operation or service=map (Only workflows may be launched)';
			return $memory;
		}
			
		/**
		 *	Get service URI and restrict access to services
		**/
		$uri = $message['service'];
		list($root, $service, $operation) = explode('.' ,$uri);
		
		/**
		 *	Remove args if set (for being on safe side)
		**/
		if(isset($message['args'])) unset($message['args']);
		
		/**
		 *	Check for valid access for service requested
		**/
		$flag = false;
		
		if(isset($access['operation']) && in_array($root.'.'.$service.'.'.$operation, $access['operation'])){
			$flag = true;
		}
		
		if(!$flag && isset($access['service']) && in_array($root.'.'.$service, $access['service'])){
			$flag = true;
		}
		
		if(!$flag && isset($access['root']) && in_array($root, $access['root'])){
			$flag = true;
		}
		
		if(!$flag){
			if(isset($access['maps']) && isset($access['maps'][$message['service']])){
				$uri = $access['maps'][$message['service']];
			}
			else {
				$memory['valid'] = false;
				$memory['msg'] = 'Access Denied';
				$memory['status'] = 500;
				$memory['details'] = "Access denied for the service : ".$message['service'];
				return $memory;
			}
		}
		
		/**
		 *	Run the service using WorkflowKernel
		**/
		unset($memory['msg']);
		$message['service'] = $uri = $uri.'.workflow';
		
		$memory['message'] = $message;
		$memory['valid'] = true;
		$memory['msg'] = 'Checked Successfully';
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