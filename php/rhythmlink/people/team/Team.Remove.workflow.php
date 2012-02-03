<?php 
require_once(SBSERVICE);

/**
 *	@class TeamRemoveWorkflow
 *	@desc Removes member from community
 *
 *	@param pnid long int Person ID [memory]
 *	@param comid long int Community ID [memory] optional default 0
 *	@param keyid long int Usage Key ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class TeamRemoveWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid', 'pnid'),
			'optional' => array('comid' => 0)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Member removed successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'comid'),
			'action' => 'remove'
		),
		array(
			'service' => 'transpera.relation.delete.workflow',
			'args' => array('comid', 'pnid'),
			'conn' => 'rtconn',
			'relation' => '`teams`',
			'sqlcnd' => "where `comid`=\${comid} and `pnid`=\${pnid}",
			'errormsg' => 'Invalid Member'
		);
		
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