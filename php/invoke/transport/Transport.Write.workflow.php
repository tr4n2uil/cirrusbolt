<?php 
require_once(SBSERVICE);

/**
 *	@class TransportWriteWorkflow
 *	@desc Builds secure message to be used further
 *
 *	@param data object Data to be secured [memory] optional default array()
 *	@param type string Encode type [memory] ('json', 'wddx', 'xml', 'plain', 'html')
 *	@param crypt string Crypt type [memory] ('none', 'rc4', 'aes', 'blowfish', 'tripledes')
 *	@param key string Key used for encryption [memory] optional default false (generated from challenge)
 *	@param challenge string Challenge to be used while hashing [memory] optional default false
 *	@param keyid string Key ID returned previously [memory] optional default false
 *	@param hash string Hash type [memory] ('none', 'md5', 'sha1', 'crc32')
 *	@param email string Email if user not set [memory] optional default false
 *	@param user string Email [memory] optional default false
 *
 *	@return result string Secured message [memory]
 *	@return key long int Key used for encryption [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class TransportWriteWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('type', 'crypt', 'hash'),
			'optional' => array('data' => array(), 'key' => false, 'keyid' => false, 'email' => false, 'challenge' => false, 'user' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		if($memory['keyid'] === false)
			$args = array('valid', 'msg', 'status', 'details', 'message', 'hash');
		else
			$args = array('user', 'challenge', 'message', 'hash', 'valid', 'msg', 'status', 'details');
		
		$workflow = array(
		array(
			'service' => 'cypher.secure.write.workflow'
		),
		array(
			'service' => 'cbcore.data.prepare.service',
			'args' => $args,
			'strict' => false,
			'valid' => $memory['valid'],
			'msg' => $memory['msg'],
			'status' => $memory['status'],
			'details' => $memory['details']
		),
		array(
			'service' => 'cbcore.data.encode.service',
			'input' => array('data' => 'result')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result', 'key');
	}
	
}

?>