<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceStateWorkflow
 *	@desc Manages editing of state value of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param state string State value [memory]
 *	@param multiple boolean Is multiple [memory] optional default false
 *
 *	@param acstate string State to authorize chain [memory] optional default true (false= All)
 *	@param action string Action to authorize member [memory] optional default 'edit'
 *	@param astate string State to authorize member [memory] optional default true (false= All)
 *	@param iaction string Action to authorize inherit [memory] optional default 'edit'
 *	@param aistate string State to authorize inherit [memory] optional default true (false= All)
 *
 *	@param cache boolean Is cacheable [memory] optional default true
 *	@param expiry int Cache expiry [memory] optional default 150
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceStateWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id', 'state'),
			'optional' => array(
				'multiple' => false,
				'acstate' => true,
				'action' => 'edit', 
				'astate' => true, 
				'iaction' => 'edit', 
				'aistate' => true,
				'cache' => true,
				'expiry' => 150
			)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference state value edited successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow'
		),
		array(
			'service' => 'guard.chain.state.workflow',
			'input' => array('chainid' => 'id')
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