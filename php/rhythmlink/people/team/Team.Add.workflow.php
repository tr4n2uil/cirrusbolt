<?php 
require_once(SBSERVICE);

/**
 *	@class TeamAddWorkflow
 *	@desc Adds new member to community
 *
 *	@param pnid long int Person ID [memory]
 *	@param keyid long int Usage Key ID [memory]
 *	@param comid long int Community ID [memory] optional default 0
 *
 *	@return tmid long int Team ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class TeamAddWorkflow implements Service {
	
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
		$memory['msg'] = 'Member added successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'comid'),
			'action' => 'add'
		),
		array(
			'service' => 'transpera.relation.insert.workflow',
			'args' => array('keyid', 'pnid', 'comid'),
			'conn' => 'rtconn',
			'relation' => '`teams`',
			'sqlcnd' => "(`owner`, `comid`, `pnid`) values (\${keyid}, \${comid}, \${pnid})",
			'output' => array('id' => 'tmid')
		));
		
		return Snowblozm::execute($workflow, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('tmid');
	}
	
}

?>