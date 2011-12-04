<?php 
require_once(SBSERVICE);

/**
 *	@class ReferenceMasterWorkflow
 *	@desc Manages editing of master key of existing reference 
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param id long int Reference ID [memory]
 *	@param keyvalue string Key value [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class ReferenceMasterWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'id', 'keyvalue')			
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Reference master key edited successfully';
		
		$workflow = array(
		array(
			'service' => 'ad.reference.authorize.workflow',
			'action' => 'edit'
		),
		array(
			'service' => 'ad.key.edit.workflow',
			'input' => array('key' => 'keyvalue', 'keyid' => 'masterkey')
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