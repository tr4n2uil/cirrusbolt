<?php 
require_once(SBSERVICE);

/**
 *	@class KeyIdentifyWorkflow
 *	@desc Identifies key from email and returns hash of it with challenge sent 
 *	@condition identifies only if (user|email) is set and (key, keyid) not set else returns what is sent
 *
 *	@param user string Email [memory] optional default false
 *	@param email string Email [memory] optional default false
 *	@param context string Application context for email [memory] optional default false
 *	@param challenge string Challenge to be used while hashing [memory] optional default 'snowblozm'
 *	@param key string Key value hash already generated previously [memory] optional default false
 *	@param keyid string Key ID returned previously [memory] optional default false
 *	@param rucache boolean Is cacheable [memory] optional default false
 *	@param ruexpiry int Cache expiry [memory] optional default 85
 *
 *	@return key string Key value hash [memory]
 *	@return keyid long int Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class KeyIdentifyWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'optional' => array(
				'challenge' => false, 
				'user' => false, 
				'email' => false, 
				'key' => false, 
				'keyid' => false, 
				'context' => false,
				'rucache' => false,
				'ruexpiry' => 85
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['challenge'] = $memory['challenge'] ? $memory['challenge'] : 'snowblozm';
		$memory['email'] = $memory['user'] ? $memory['user'] : ($memory['email'] ? $memory['email'] : false);
		
		if($memory['email'] !==false && $memory['key'] === false && $memory['keyid'] === false){
			$memory['msg'] = 'Key identified successfully';
			
			$workflow = array(
			array(
				'service' => 'transpera.relation.unique.workflow',
				'args' => array('email', 'challenge', 'context'),
				'conn' => 'cbconn',
				'relation' => '`keys`',
				'sqlprj' => "keyid, MD5(concat(`keyvalue`,'\${challenge}')) as `key`",
				'sqlcnd' => "where `email`='\${email}' and `context` like '%\${context}%'",
				'escparam' => array('email', 'challenge', 'context'),
				'errormsg' => 'Unable to identify key from email'
			),
			array(
				'service' => 'cbcore.data.select.service',
				'args' => array('result'),
				'params' => array('result.0.keyid' => 'keyid', 'result.0.key' => 'key')
			));
			
			$memory = Snowblozm::execute($workflow, $memory);
		}
		else {
			$memory['valid'] = true;
		}
		
		return $memory;
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('keyid', 'key');
	}
	
}

?>