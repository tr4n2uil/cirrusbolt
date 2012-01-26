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
 *	@param cache boolean Is cacheable [memory] optional default true
 *	@param expiry int Cache expiry [memory] optional default 150
 *	@param authinh integer Check inherit [memory] optional default 1
 *	@param autherror string Error msg [memory] optional default 'Unable to Authorize'
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
			'required' => array('keyid', 'id', 'keyvalue'),
			'optional' => array('authinh' => 1, 'autherror' => 'Unable to Authorize', 'cache' => true, 'expiry' => 150)
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