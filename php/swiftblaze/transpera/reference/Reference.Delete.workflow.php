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
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'remove'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'remove'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *
 *	@param cache boolean Is cacheable [memory] optional default true
 *	@param expiry int Cache expiry [memory] optional default 150
 *	@param authinh integer Check inherit [memory] optional default 1
 *	@param autherror string Error msg [memory] optional default 'Unable to Authorize'
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
			'optional' => array(
				'type' => 'general', 
				'acstate' => true,
				'action' => 'remove', 
				'astate' => true, 
				'iaction' => 'remove', 
				'aistate' => true,
				'authinh' => 1,
				'autherror' => 'Unable to Authorize',
				'cache' => true,
				'expiry' => 150
			)
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
		),
		array(
			'service' => 'guard.chain.count.workflow',
			'input' => array('chainid' => 'parent'),
			'remove' => true
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