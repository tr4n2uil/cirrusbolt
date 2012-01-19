<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceDeleteWorkflow
 *	@desc Manages deletion of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param parent long int Reference ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceDeleteWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'parent', 'id'),
			'optional' => array('type' => 'general')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference deleted successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'action' => 'remove'
		),
		array(
			'service' => 'guard.key.remove.workflow',
			'input' => array('keyid' => 'masterkey')
		),
		array(
			'service' => 'guard.chain.remove.workflow',
			'input' => array('chainid' => 'id')
		),
		array(
			'service' => 'guard.member.delete.workflow',
			'input' => array('chainid' => 'id')
		),
		array(
			'service' => 'guard.web.remove.workflow',
			'input' => array('child' => 'id')
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