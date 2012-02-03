<?php 
require_once(SBSERVICE);

/**
 *	@class CommunityAddWorkflow
 *	@desc Adds new community to person
 *
 *	@param name string Community name [memory]
 *	@param role long int Role ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *
 *	@return comid long int Community ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommunityAddWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'name', 'role'),
			'optional' => array('pnid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.add.workflow',
			'args' => array('name', 'role'),
			'input' => array('parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`communities`',
			'sqlcnd' => "(`comid`, `owner`, `name`, `role`) values (\${id}, \${owner}, '\${name}', \${role})",
			'escparam' => array('name'),
			'type' => 'community',
			'successmsg' => 'Community added successfully',
			'output' => array('id' => 'comid')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('comid');
	}
	
}

?>