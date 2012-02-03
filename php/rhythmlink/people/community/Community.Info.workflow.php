<?php 
require_once(SBSERVICE);

/**
 *	@class CommunityInfoWorkflow
 *	@desc Returns community information by ID
 *
 *	@param comid string Community ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default 0
 *
 *	@return community array Community information [memory]
 *	@return pnid long int Person ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class CommunityInfoWorkflow implements Service {
	
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
			'service' => 'transpera.entity.info.workflow',
			'input' => array('id' => 'comid', 'parent' => 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`communities`',
			'sqlcnd' => "where `comid`='\${id}'",
			'errormsg' => 'Invalid Community ID',
			'successmsg' => 'Community information given successfully',
			'output' => array('entity' => 'community')
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('community', 'pnid', 'admin');
	}
	
}

?>
