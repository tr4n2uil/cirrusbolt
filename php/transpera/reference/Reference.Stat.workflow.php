<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceStatWorkflow
 *	@desc Returns statistics of reference
 *
 *	@param id long int Reference ID [memory]
 *
 *	@return stat array Statistics [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceStatWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('id')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'ad.reference.authorize.workflow',
			'input' => array('chainid' => 'id'),
			'action' => 'info'
		),
		array(
			'service' => 'ad.chain.stat.workflow',
			'input' => array('chainid' => 'id')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stat');
	}
	
}

?>