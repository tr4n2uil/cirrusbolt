<?php 
require_once(SBSERVICE);

/**
 *	@class CommunityRemoveWorkflow
 *	@desc Removes community by ID
 *
 *	@param comid long int Community ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommunityRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'comid'),
			'optional' => array('pnid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.entity.remove.workflow',
			'input' => array('id' => 'comid', 'parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`communities`',
			'sqlcnd' => "where `comid`=\${id}",
			'errormsg' => 'Invalid Community ID',
			'successmsg' => 'Community removed successfully'
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