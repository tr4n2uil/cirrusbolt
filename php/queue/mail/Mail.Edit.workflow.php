<?php 
require_once(SBSERVICE);

/**
 *	@class RoleEditWorkflow
 *	@desc Edits role using ID
 *
 *	@param rlid long int Role ID [memory]
 *	@param name string Person name [memory]
 *	@param desc string Role description [memory] optional default ''
 *	@param priority string Role priority [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RoleEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'rlid', 'name', 'desc', 'priority')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('name', 'desc', 'priority'),
			'input' => array('id' => 'rlid'),
			'conn' => 'rlconn',
			'relation' => '`roles`',
			'sqlcnd' => "set `name`='\${name}', `desc`='\${desc}', `priority`=\${priority} where `rlid`=\${id}",
			'escparam' => array('name', 'desc'),
			'successmsg' => 'Role edited successfully'
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