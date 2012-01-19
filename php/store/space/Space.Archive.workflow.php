<?php 
require_once(SBSERVICE);

/**
 *	@class SpaceArchiveWorkflow
 *	@desc Archives space storages and downloads it
 *
 *	@param spaceid long int Space ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param asname string As name [memory] optional default 'archive.zip'
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SpaceArchiveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'spaceid'),
			'optional' => array('asname' => 'archive.zip')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Space archived successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'spaceid'),
			'action' => 'edit'
		),
		array(
			'service' => 'store.space.info.workflow'
		),
		array(
			'service' => 'cbcore.file.archive.service',
			'input' => array('directory' => 'sppath')
		),
		array(
			'service' => 'cbcore.file.download.service',
			'input' => array('filepath' => 'sppath'),
			'filename' => 'archive.zip',
			'mime' => 'application/zip'
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