<?php 
require_once(SBSERVICE);

/**
 *	@class SelectionListWorkflow
 *	@desc Returns all selections information for shortlist
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param shlstid long int Shortlist ID [memory] optional default 0
 *	@param evname string Series name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return selections array Selections information [memory]
 *	@return evname string Shortlist name [memory]
 *	@return shlstid long int Shortlist ID [memory]
 *	@return admin integer Is admin [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class SelectionListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('shlstid' => 0, 'shlstname' => '', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service =  array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'shlstid'),
			'conn' => 'cbslconn',
			'relation' => '`selections`',
			'sqlcnd' => "where selid in \${list} order by `stageid` desc",
			'output' => array('entities' => 'selections'),
			'successmsg' => 'Selections information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('selections', 'shlstid', 'evname', 'admin');
	}
	
}

?>