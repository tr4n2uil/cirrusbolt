<?php 
require_once(SBSERVICE);

/**
 *	@class StorageInfoWorkflow
 *	@desc Returns storage information by ID
 *
 *	@param stgid long int Storage ID [memory]
 *	@param keyid long int Usage Key ID [memory] optional default false
 *	@param spaceid long int Space ID [memory] optional default 0
 *	@param spname string Space name [memory] optional default ''
 *
 *	@return stgid long int Storage ID [memory]
 *	@return spaceid long int Space ID [memory]
 *	@return spname string Space name [memory]
 *	@return owner long int Owner [memory]
 *	@return stgname string Storage name [memory]
 *	@return filename string File name [memory]
 *	@return mime string MIME [memory]
 *	@return size long int Size in bytes [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageInfoWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('stgid'),
			'optional' => array('keyid' => false, 'spname' => '', 'spaceid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Storage information given successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'stgid', 'parent' => 'spaceid'),
			'conn' => 'tsconn',
			'relation' => '`storages`',
			'sqlcnd' => "where `stgid`=\${id}",
			'errormsg' => 'Invalid Storage ID',
			'output' => array('entity' => 'storage')
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('storage'),
			'params' => array('storage.filename' => 'filename', 'storage.mime' => 'mime', 'storage.size' => 'size', 'storage.stgname' => 'stgname', 'storage.owner' => 'owner')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stgid', 'spaceid', 'spname', 'storage', 'owner', 'stgname', 'filename', 'mime', 'size');
	}
	
}

?>