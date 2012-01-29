<?php 
require_once(SBSERVICE);

/**
 *	@class RoleRemoveWorkflow
 *	@desc Removes role by ID
 *
 *	@param rlid long int Role ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RoleRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'rlid'),
			'optional' => array('pnid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$workflow = array(
		array(
			'service' => 'people.role.info.workflow'
		),
		array(
			'service' => 'transpera.entity.remove.workflow',
			'input' => array('id' => 'rlid', 'parent' => 'pnid'),
			'conn' => 'rlconn',
			'relation' => '`roles`',
			'sqlcnd' => "where `rlid`=\${id}",
			'errormsg' => 'Invalid Role ID',
			'successmsg' => 'Role removed successfully',
			'destruct' => array(
				'service' => 'storage.file.remove.workflow',
				'input' => array('fileid' => 'thumbnail')
				'dirid' => 3
			)
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