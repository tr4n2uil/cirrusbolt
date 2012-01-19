<?php 
require_once(SBSERVICE);

/**
 *	@class InterfaceSessionWorkflow
 *	@desc Processes interface session in Tile UI
 *
 *	@param email string Identification email to be authenticated [memory] optional default false
 *	@param key string Identification password to be authenticated [memory] optional default false
 *	@param continue string URL to continue [memory] optional default false
 *
 *	@param session array Session instance configuration key [memory] ('key', 'expiry', 'root', 'context')
 *
 *	@return email string Email [memory]
 *	@return key string Cookie name [memory]
 *	@return value string Session ID [memory]
 *	@return expiry string Cookie expiry [memory]
 *	@return continue string URL to continue [memory] optional default false
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *	
**/
class InterfaceSessionWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array('email' => false, 'key' => false, 'continue' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$session = Snowblozm::get('session');
		$memory['value'] = false;
		$memory['expires'] = false;
		
		if($memory['email'] && $memory['key']){
		
			$workflow = array(
			array(
				'service' => 'guard.key.authenticate.workflow',
				'context' => $session['context']
			),
			array(
				'service' => 'cbcore.session.add.workflow',
				'expiry' => $session['expiry'],
				'output' => array('sessionid' => 'value')
			));
				
			$memory = Snowblozm::execute($workflow, $memory);
			
			if($memory['valid']) {
				$memory['key'] = $session['key'];
				$memory['expires'] = $session['expiry'];
			}
			else {
				return $memory;
			}
		}
		else if(isset($_COOKIE[$session['key']])){
			$memory['key'] = false;
			
			$service = array(
				'service' => 'cbcore.session.remove.workflow',
				'sessionid' => $_COOKIE[$session['key']]
			);
					
			$memory = Snowblozm::run($service, $memory);
					
			if($memory['valid']){
				header('Location: '. $session['root']);
				exit;
			}
			else {
				$memory['valid'] = false;
				$memory['msg'] = 'Invalid Session';
				$memory['status'] = 500;
				$memory['details'] = "Session logout requested was invalid due to possible expiry of previous session @interface.session";
				return $memory;
			}
					
		}
		else {
			$memory['valid'] = false;
			$memory['msg'] = 'Invalid Request';
			$memory['status'] = 500;
			$memory['details'] = "Session action requested was invalid due to possible expiry of previous session @interface.session";
			return $memory;
		}
		
		$memory['valid'] = true;
		$memory['msg'] = 'Valid Interface Session';
		$memory['status'] = 200;
		$memory['details'] = 'Successfully executed';
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('email', 'key', 'value', 'expires', 'continue');
	}
	
}

?>