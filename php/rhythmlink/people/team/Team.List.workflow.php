<?php 
require_once(SBSERVICE);

/**
 *	@class TeamListWorkflow
 *	@desc Returns all members in community
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param comid long int Community ID [memory] optional default 0
 *	@param comname string Community name [memory] optional default ''
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return members array Member information [memory]
 *	@return comid long int Community ID [memory]
 *	@return comname string Community name [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class TeamListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('comid' => 0, 'comname' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Member information given successfully';
		
		$workflow = array(
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'comid'),
			'action' => 'list'
		),
		array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('comid'),
			'conn' => 'rtconn',
			'relation' => '`teams`',
			'sqlcnd' => "where `comid`=\${comid}",
			'check' => false,
			'output' => array('result' => 'members'),
		),
		array(
			'service' => 'transpera.reference.authorize.workflow',
			'input' => array('id' => 'comid'),
			'admin' => true,
			'action' => 'add'
		));
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('members', 'comid', 'comname', 'admin');
	}
	
}

?>