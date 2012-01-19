<?php 
require_once(SBSERVICE);

/**
 *	@class StorageReadWorkflow
 *	@desc Reads storage information and downloads file by ID
 *
 *	@param stgid long int Storage ID [memory]
 *	@param spaceid long int Space ID [memory] optional default 0
 *	@param asname string As name [memory] optional default false
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageReadWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('stgid'),
			'optional' => array('spaceid' => 0, 'asname' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'stgid'),
			'action' => 'read'
		),
		array(
			'service' => 'store.space.info.workflow',
			'output' => array('sppath' => 'filepath')
		),
		array(
			'service' => 'store.storage.info.workflow',
			'output' => array('stgname' => 'asname')
		),
		array(
			'service' => 'cbcore.file.download.service'
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