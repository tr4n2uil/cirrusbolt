<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceRevokeWorkflow
 *	@desc Manages revoking of privileges to existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param childkeyid long int Key ID to be revoked [memory]
 *
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'edit'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
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
class ReferenceRevokeWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id', 'childkeyid'),
			'optional' => array(
				'acstate' => true,
				'action' => 'edit', 
				'astate' => true, 
				'iaction' => 'edit', 
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
		$memory['msg'] = 'Reference privilege revoked successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'guard.member.remove.workflow',
			'input' => array('chainid' => 'id', 'keyid' => 'childkeyid')
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