<?php 
require_once(SBSERVICE);

/**
 *	@class KeyAvailableWorkflow
 *	@desc Checks for availability of service key value
 *
 *	@param email string Email [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class KeyAvailableWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('email')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Email available for registration';
		
		$service = array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('email'),
			'conn' => 'cbconn',
			'relation' => '`keys`',
			'sqlprj' => 'keyid',
			'sqlcnd' => "where `email`='\${email}'",
			'escparam' => array('email'),
			'not' => false,
			'errormsg' => 'Email already registered'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>