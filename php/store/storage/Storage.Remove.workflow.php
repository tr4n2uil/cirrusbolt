<?php 
require_once(SBSERVICE);

/**
 *	@class StorageRemoveWorkflow
 *	@desc Removes storage by ID
 *
 *	@param stgid long int Storage ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param spaceid long int Space ID [memory] optional default 0
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'stgid'),
			'optional' => array('spaceid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Storage removed successfully';
		
		$workflow = array(
		array(
			'service' => 'store.space.info.workflow',
			'output' => array('sppath' => 'filepath')
		),
		array(
			'service' => 'store.storage.info.workflow'
		),
		array(
			'service' => 'transpera.reference.remove.workflow',
			'input' => array('parent' => 'spaceid', 'id' => 'stgid')
		),
		array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('stgid'),
			'conn' => 'cbconn',
			'relation' => '`storages`',
			'sqlcnd' => "where `stgid`=\${stgid}",
			'errormsg' => 'Invalid Storage ID'
		),
		array(
			'service' => 'cbcore.file.unlink.service'
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