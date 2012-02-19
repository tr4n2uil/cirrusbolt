<?php 
require_once(SBSERVICE);

/**
 *	@class RoleAddWorkflow
 *	@desc Adds new role to person
 *
 *	@param name string Role name [memory]
 *	@param desc string Role description [memory] optional default ''
 *	@param priority string Role priority [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *	@param user string Key User [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *	@param owner long int Owner Key ID [memory] optional default keyid
 *
 *	@return rlid long int Role ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RoleAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'user', 'name'),
			'optional' => array('desc' => '', 'pnid' => 0, 'priority' => 0, 'owner' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('name', 'desc', 'priority'),
			'input' => array('parent' => 'pnid'),
			'authorize' => 'info:edit:add:remove:list',
			'conn' => 'rlconn',
			'relation' => '`roles`',
			'sqlcnd' => "(`rlid`, `owner`, `name`, `desc`, `priority`, `thumbnail`) values (\${id}, \${owner}, '\${name}', '\${desc}', \${priority}, \${thumbnail})",
			'escparam' => array('name', 'desc'),
			'type' => 'role',
			'successmsg' => 'Role added successfully',
			'output' => array('id' => 'rlid'),
			'construct' => array(
				array(
					'service' => 'storage.file.add.workflow',
					'input' => array('name' => 'id'),
					'ext' => 'png',
					'mime' => 'image/png',
					'dirid' => ROLE_THUMB,
					'output' => array('fileid' => 'thumbnail')
				)
			),
			'cparam' => array('thumbnail')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('rlid');
	}
	
}

?>