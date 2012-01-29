<?php 
require_once(SBSERVICE);

/**
 *	@class RoleInfoWorkflow
 *	@desc Returns role information by ID
 *
 *	@param rlid string Role ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *
 *	@return role array Role information [memory]
 *	@return pnid long int Person ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RoleInfoWorkflow implements Service {
	
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
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'rlid', 'parent' => 'pnid'),
			'conn' => 'rlconn',
			'relation' => '`roles`',
			'sqlcnd' => "where `rlid`='\${id}'",
			'errormsg' => 'Invalid Role ID',
			'successmsg' => 'Role information given successfully',
			'output' => array('entity' => 'role')
		),
		array(
			'service' => 'cbcore.data.select.service',
			'args' => array('role'),
			'params' => array('role.thumbnail' => 'thumbnail')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('role', 'pnid', 'admin', 'thumbnail');
	}
	
}

?>
