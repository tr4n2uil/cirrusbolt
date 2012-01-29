<?php 
require_once(SBSERVICE);

/**
 *	@class DirectoryListWorkflow
 *	@desc Returns all directories information in storage
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param stgid long int Storage ID [memory] optional default 0
 *	@param stgname string Storage name [memory] optional default ''
 *
 *	@return directories array Directory information [memory]
 *	@return stgid long int Storage ID [memory]
 *	@return stgname string Storage name [memory]
 *	@return admin integer Is admin [memory] 
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DirectoryListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('stgid' => 0, 'stgname' => '')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.enitity.list.workflow',
			'input' => array('id' => 'stgid'),
			'conn' => 'cbsconn',
			'relation' => '`directories`',
			'sqlprj' => '`dirid`, `owner`, `name`',
			'sqlcnd' => "where `dirid` in \${list} order by `name`",
			'output' => array('entities' => 'directories'),
			'successmsg' => 'Directories information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('directories', 'admin', 'stgid', 'stgname');
	}
	
}

?>