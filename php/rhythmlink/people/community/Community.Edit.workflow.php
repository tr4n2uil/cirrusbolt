<?php 
require_once(SBSERVICE);

/**
 *	@class CommunityEditWorkflow
 *	@desc Edits community using ID
 *
 *	@param comid long int Community ID [memory]
 *	@param name string Community name [memory]
 *	@param role long int Role ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommunityEditWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'comid', 'name', 'role')
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.edit.workflow',
			'args' => array('name', 'role'),
			'input' => array('id' => 'comid'),
			'conn' => 'rtconn',
			'relation' => '`communities`',
			'sqlcnd' => "set `name`='\${name}', `role`=\${role} where `comid`=\${id}",
			'escparam' => array('name'),
			'successmsg' => 'Community edited successfully'
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