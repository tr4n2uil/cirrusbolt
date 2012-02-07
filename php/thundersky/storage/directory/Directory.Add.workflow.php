<?php 
require_once(SBSERVICE);

/**
 *	@class DirectoryAddWorkflow
 *	@desc Adds new directory to storage
 *
 *	@param name string Directory name [memory]
 *	@param path string Directory path [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param stgid long int Storage ID [memory] optional default 0
 *	@param level integer Web level [memory] optional default 1 (storage admin access allowed)
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *
 *	@return dirid long int Directory ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class DirectoryAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'name', 'path'),
			'optional' => array('stgid' => 0, 'level' => 1, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['owner'] = $memory['owner'] ? $memory['owner'] : $memory['keyid'];
		$memory['msg'] = 'Directory added successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.add.workflow',
			'input' => array('parent' => 'stgid'),
			'type' => 'directory',
			'output' => array('id' => 'dirid')
		),
		array(
			'service' => 'cbcore.file.mkdir.service',
			'input' => array('directory' => 'path')
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('dirid', 'owner', 'name', 'path'),
			'conn' => 'cbsconn',
			'relation' => '`directories`',
			'sqlcnd' => "(`dirid`, `owner`, `name`, `path`) values (\${dirid}, \${owner}, '\${name}', '\${path}')",
			'escparam' => array('name', 'path')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('dirid');
	}
	
}

?>