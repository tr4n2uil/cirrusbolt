<?php 
require_once(SBSERVICE);

/**
 *	@class PersonVerifyWorkflow
 *	@desc Changes key for person by ID
 *
 *	@param email string Person email [memory]
 *	@param phone string Person phone [memory] optional default false
 *	@param verify string Verification code [memory]
 *	@param context string Context [constant as CONTEXT]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class PersonVerifyWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('email', 'verify'),
			'optional' => array('phone' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		if($memory['verify'] == ''){
			$memory['valid'] = false;
			$memory['msg'] = 'Invalid verification code';
			$memory['status'] = 500;
			$memory['details'] = "Person verification code : ".$memory['verify']." is invalid @people.person.verify";
			return $memory;
		}
		
		$memory['msg'] = 'Person verified successfully';
		$attr = $memory['phone'] ? 'phone' : 'email';
		$memory['phone'] = $memory['phone'] ? $memory['phone'] : $memory['email'];
		
		$workflow = array(
		array(
			'service' => 'transpera.relation.unique.workflow',
			'args' => array('phone', 'verify'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "where `$attr`='\${phone}' and `verify`='\${verify}'",
			'escparam' => array('phone', 'verify'),
			'errormsg' => 'Invalid Verification Code'
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('result'),
			'params' => array('result.0.owner' => 'owner')
		),
		array(
			'service' => 'guard.key.verify.workflow',
			'input' => array('keyid' => 'owner'),
			'context' => CONTEXT
		),
		array(
			'service' => 'transpera.relation.update.workflow',
			'args' => array('owner'),
			'conn' => 'rlconn',
			'relation' => '`persons`',
			'sqlcnd' => "set `verify`='', `device`='' where `owner`=\${owner}",
			'errormsg' => 'Invalid Person'
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>