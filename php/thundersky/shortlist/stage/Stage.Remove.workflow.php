<?php 
require_once(SBSERVICE);

/**
 *	@class StageRemoveWorkflow
 *	@desc Removes stage by ID
 *
 *	@param stageid long int Stage ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param shlstid long int Shortlist ID [memory] optional default 0
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StageRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'stageid'),
			'optional' => array('shlstid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'ad.reference.remove.workflow',
			'input' => array('id' => 'stageid', 'parent' => 'shlstid'),
			'conn' => 'cbslconn',
			'relation' => '`stages`',
			'sqlcnd' => "where `stageid`=\${id}",
			'errormsg' => 'Invalid Stage ID',
			'successmsg' => 'Stage removed successfully'
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