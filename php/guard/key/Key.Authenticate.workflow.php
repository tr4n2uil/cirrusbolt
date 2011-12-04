<?php 
require_once(SBSERVICE);

/**
 *	@class KeyAuthenticateWorkflow
 *	@desc Validates email keyvalue and selects key ID
 *
 *	@param email string Email [memory]
 *	@param key string Usage key [memory]
 *	@param context string Application context for email [memory] optional default false
 *
 *	@return keyid long int Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class KeyAuthenticateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('key', 'email'),
			'optional' => array('context' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Key authenticated successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.relation.unique.workflow',
			'args' => array('key', 'email', 'context'),
			'conn' => 'adconn',
			'relation' => '`keys`',
			'sqlprj' => 'keyid',
			'sqlcnd' => "where `email`='\${email}' and `context` like '%\${context}%' and `keyvalue`=MD5('\${email}\${key}')",
			'escparam' => array('key', 'email', 'context'),
			'errormsg' => 'Invalid Credentials'
		),
		array(
			'service' => 'adcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.keyid' => 'keyid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('keyid');
	}
	
}

?>