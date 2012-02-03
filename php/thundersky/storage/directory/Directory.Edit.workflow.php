<?php 
require_once(SBSERVICE);

/**
 *	@class DirectoryEditWorkflow
 *	@desc Edits directory of storage
 *
 *	@param dirid long int Directory ID [memory]
 *	@param name string Directory name [memory]
 *	@param path string Directory path [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DirectoryEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'dirid', 'name', 'path')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'input' => array('id' => 'dirid'),
			'args' => array('name', 'path'),
			'conn' => 'cbsconn',
			'relation' => '`directories`',
			'sqlcnd' => "set `name`='\${name}', `path`='\${path}' where `dirid`=\${id}",
			'escparam' => array('name', 'path'),
			'successmsg' => 'Directory edited successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array();
	}
	
}

?>