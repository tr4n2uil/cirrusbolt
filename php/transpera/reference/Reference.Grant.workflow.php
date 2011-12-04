<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceGrantWorkflow
 *	@desc Manages granting of privileges to existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param childkeyid long int Key ID to be granted [memory]
 *
 *	@return return id long int Chain member ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceGrantWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id', 'childkeyid')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference privilege granted successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.reference.authorize.workflow',
			'action' => 'edit'
		),
		array(
			'service' => 'ad.chain.add.workflow',
			'input' => array('chainid' => 'id', 'keyid' => 'childkeyid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('id');
	}
	
}

?>