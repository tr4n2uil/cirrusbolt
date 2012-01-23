<?php 
require_once(SBSERVICE);

/**
 *	@class LaunchMessageWorkflow
 *	@desc Launches workflows from messages
 *
 *	@param reqtype string request type [memory] ('get', 'post', 'json', 'wddx', 'xml')
 *	@param restype string response types [memory] ('json', 'wddx', 'xml', 'plain', 'html'),
 *	@param crypt string Crypt types [memory] ('none', 'rc4', 'aes', 'blowfish', 'tripledes')
 *	@param hash string Hash types [memory] ('none', 'md5', 'sha1', 'crc32')
 *	@param access array allowed service provider names [memory] optional default false
 *	@param email string Identification email to be used if not set in message [memory] optional default false
 *	@param context string Application context for email [memory] optional default false
 * 	@param raw boolean Raw output required [memory] optional default false
 *	@param uiconf array UI data [memory]
 *
 *	@result result string Result [memory]
 *	@result response object Response [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class LaunchMessageWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('reqtype', 'restype', 'crypt' , 'hash'),
			'optional' => array('access' => array(), 'email' => false, 'context' => false, 'raw' => false, 'uiconf' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'invoke.http.read.service'
		),
		array(
			'service' => 'invoke.transport.read.workflow',
			'input' => array('type' => 'reqtype')
		),
		array(
			'service' => 'invoke.launch.check.service',
			'input' => array('message' => 'result')
		),
		array(
			'service' => 'invoke.launch.message.service'
		),
		array(
			'service' => 'invoke.transport.write.workflow',
			'input' => array('data' => 'response', 'type' => 'restype'),
			'strict' => false
		));
		
		if(!$memory['raw']){
			array_push($workflow, array(
				'service' => 'invoke.http.write.service',
				'input' => array('data' => 'result', 'type' => 'restype')
			));
		}
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result', 'response');
	}
	
}

?>