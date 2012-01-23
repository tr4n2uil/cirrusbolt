<?php 
require_once(SBSERVICE);

/**
 *	@class StorageListWorkflow
 *	@desc Returns all storages information in space
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param spaceid long int Space ID [memory] optional default 0
 *	@param spname string Space name [memory] optional default ''
 *	@param sppath string Space path [memory] optional default 'storage/directory/'
 *
 *	@return storages array Storage information [memory]
 *	@return spaceid long int Space ID [memory]
 *	@return spname string Space name [memory]
 *	@return sppath string Space path [memory]
 *	@return admin integer Is admin [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StorageListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('spaceid' => 0, 'spname' => '', 'sppath' => 'storage/directory/')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'spaceid'),
			'conn' => 'cbconn',
			'relation' => '`storages`',
			'sqlprj' => '`stgid`, `stgname`, `mime`, `size`',
			'sqlcnd' => "where `stgid` in \${list} order by `stgname`",
			'output' => array('entities' => 'storages'),
			'successmsg' => 'Storages information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('storages', 'admin', 'spaceid', 'spname', 'sppath');
	}
	
}

?>