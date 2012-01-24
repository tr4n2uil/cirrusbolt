<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceRemoveWorkflow
 *	@desc Manages removal of existing reference 
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
 *	@param arucache boolean Is cacheable [memory] optional default true
 *	@param aruexpiry int Cache expiry [memory] optional default 150
 *	@param asrucache boolean Is cacheable [memory] optional default true
 *	@param asruexpiry int Cache expiry [memory] optional default 150
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceRemoveWorkflow implements Service {
	
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
				'arucache' => true,
				'aruexpiry' => 150,
				'asrucache' => true,
				'asruexpiry' => 150
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
			'input' => array('id' => 'parent')
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