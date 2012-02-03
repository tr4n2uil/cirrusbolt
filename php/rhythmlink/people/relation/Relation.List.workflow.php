<?php 
require_once(SBSERVICE);

/**
 *	@class RelationListWorkflow
 *	@desc Returns all persons information in relation with person
 *
 *	@param keyid long int Usage Key ID [memory]
 *	@param pnid long int Person ID [memory] optional default owner
 *	@param state string Relation state [memory] optional default 'C'
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return relations array Persons information [memory]
 *	@return pnid long int Person ID [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class RelationListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('keyid'),
			'optional' => array('pnid' => 0, 'state' => 'C', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('pnid', 'state'),
			'conn' => 'rtconn',
			'relation' => '`relations`',
			'sqlcnd' => "where `from`=\${pnid} and `state`=\${state}",
			'check' => false,
			'successmsg' => 'Relations information given successfully'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('relations', 'pnid');
	}
	
}

?>