<?php 
require_once(SBSERVICE);

/**
 *	@class RoleListWorkflow
 *	@desc Returns all roles information in person container
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid/id long int Person ID [memory] optional default 0
 *	@param username string Person name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@param user string User email [memory] optional default 'unknown@role.list'
 *
 *	@return roles array Roles information [memory]
 *	@return pnid long int Person ID [memory]
 *	@return username string Person name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RoleListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('pnid' => 0, 'username' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false, 'user' => 'unknown@role.list', 'id' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['pnid'] = $memory['pnid'] ? $memory['pnid'] : $memory['id'];
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'pnid'),
			'conn' => 'rlconn',
			'relation' => '`roles`',
			'sqlcnd' => "where `rlid` in \${list} order by `priority` desc",
			'type' => 'role',
			'successmsg' => 'Roles information given successfully',
			'output' => array('entities' => 'roles'),
			'mapkey' => 'rlid',
			'mapname' => 'role'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('roles', 'pnid', 'username', 'admin');
	}
	
}

?>