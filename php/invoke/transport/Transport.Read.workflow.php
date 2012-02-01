<?php 
require_once(SBSERVICE);

/**
 *	@class TransportReadWorkflow
 *	@desc Unsecures secure message to be used further
 *
 *	@param data string Data to be unsecured [memory]
 *	@param type string Decode type [memory] ('json', 'wddx', 'xml', 'plain', 'html')
 *	@param crypt string Crypt type [memory] ('none', 'rc4', 'aes', 'blowfish', 'tripledes')
 *	@param key string Key used for decryption [memory] optional default false (generated from challenge)
 *	@param keyid string Key ID returned previously [memory] optional default false
 *	@param hash string Hash type [memory] ('none', 'md5', 'sha1', 'crc32')
 *	@param user string Username [memory] optional default false
 *	@param context string Application context for email [memory] optional default false
 *
 *	@return result object Unsecured message [memory]
 *	@return key long int Key used for decryption [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class TransportReadWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('data', 'type', 'crypt', 'hash'),
			'optional' => array('key' => false, 'keyid' => false, 'user' => false, 'context' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'cbcore.data.decode.service',
			'output' => array('result' => 'message')
		),
		array(
			'service' => 'cypher.secure.read.workflow',
			'input' => array('data' => 'message')
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