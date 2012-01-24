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
 *	@param arucache boolean Is cacheable [memory] optional default true
 *	@param aruexpiry int Cache expiry [memory] optional default 150
 *	@param asrucache boolean Is cacheable [memory] optional default true
 *	@param asruexpiry int Cache expiry [memory] optional default 150
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
			'optional' => array(
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
		$memory['msg'] = 'Reference master key edited successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'action' => 'edit'
		),
		array(
			'service' => 'guard.key.edit.workflow',
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