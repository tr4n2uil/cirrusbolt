<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceControlWorkflow
 *	@desc Manages editing of authorize control value of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param authorize string Control value [memory]
 *	@param miltiple boolean Is multiple [memory] optional default false
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceControlWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id', 'authorize'),
			'optional' => array('multiple' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference control value edited successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.reference.authorize.workflow',
			'input' => array('chainid' => 'id'),
			'action' => 'edit'
		),
		array(
			'service' => 'ad.chain.control.workflow',
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