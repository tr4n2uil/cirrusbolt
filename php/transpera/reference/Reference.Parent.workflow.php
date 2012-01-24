<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceParentWorkflow
 *	@desc Manages parent finding of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param state string State [memory] optional default false (true= Not '0')
 *
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'list'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'list'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *
 *	@param wrucache boolean Is cacheable [memory] optional default false
 *	@param wruexpiry int Cache expiry [memory] optional default 85
 *
 *	@return web array Web member information [memory]
 *	@return parent long int Chain ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceParentWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id'),
			'optional' => array(
				'type' => 'general', 
				'state' => true,
				'acstate' => true,
				'action' => 'list', 
				'astate' => true, 
				'iaction' => 'list', 
				'aistate' => true,
				'arucache' => true,
				'aruexpiry' => 150,
				'asrucache' => true,
				'asruexpiry' => 150,
				'wrucache' => false,
				'wruexpiry' => 85
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference parent returned successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'guard.web.parent.workflow',
			'input' => array(
				'child' => 'id',
				'rucache' => 'wrucache',
				'ruexpiry' => 'wruexpiry'
			)
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('parent', 'web');
	}
	
}

?>