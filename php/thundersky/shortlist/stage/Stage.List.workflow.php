<?php 
require_once(SBSERVICE);

/**
 *	@class StageListWorkflow
 *	@desc Returns all stages information in shortlist
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param asc boolean Is ascending [memory] optional default false
 *	@param shlstid/id long int Shortlist ID [memory] optional default 0
 *	@param ename string Shortlist name [memory] optional default ''
 *
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return stages array Stages information [memory]
 *	@return ename string Shortlist name [memory]
 *	@return shlstid long int Shortlist ID [memory]
 *	@return admin integer Is admin [memory]
 *	@return total long int Total count [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class StageListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('shlstid' => 0, 'asc' => false, 'ename' => '', 'id' => 0, 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['shlstid'] = $memory['shlstid'] ? $memory['shlstid'] : $memory['id'];
		$order = $memory['asc'] ? 'asc' : 'desc';
		
		$service = array(
			'service' => 'transpera.entity.list.workflow',
			'input' => array('id' => 'shlstid'),
			'conn' => 'cbslconn',
			'relation' => '`stages`',
			'sqlcnd' => "where `stageid` in \${list} order by `start` $order",
			'output' => array('entities' => 'stages'),
			'successmsg' => 'Stages information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('stages', 'ename', 'shlstid', 'admin', 'total');
	}
	
}

?>