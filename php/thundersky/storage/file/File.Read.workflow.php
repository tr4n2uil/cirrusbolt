<?php 
require_once(SBSERVICE);

/**
 *	@class FileReadWorkflow
 *	@desc Reads file information and downloads file by ID
 *
 *	@param fileid long int File ID [memory]
 *	@param dirid long int Directory ID [memory] optional default 0
 *	@param asname string As name [memory] optional default false
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class FileReadWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('fileid'),
			'optional' => array('dirid' => 0, 'asname' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'fileid'),
			'action' => 'read'
		),
		array(
			'service' => 'storage.directory.info.workflow',
			'output' => array('path' => 'filepath')
		),
		array(
			'service' => 'storage.file.info.workflow',
			'output' => array('name' => 'asname')
		),
		array(
			'service' => 'storage.file.download.service'
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