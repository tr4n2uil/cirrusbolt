<?php 
require_once(SBSERVICE);

/**
 *	@class OpenidRemoveWorkflow
 *	@desc Removes openid email
 *
 *	@param keyid long int Key ID [memory]
 *	@param email string Email ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class OpenidRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'email')
		);
	}

	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Openid Email Removed Successfully';
		
		$service = array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('keyid', 'email'),
			'conn' => 'cbconn',
			'relation' => '`openids`',
			'sqlcnd' => "where `email`='\${email}' and `keyid`=\${keyid}",
			'escparam' => array('email'),
			'errormsg' => 'Invalid Openid'
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