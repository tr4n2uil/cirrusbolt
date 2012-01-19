<?php 
require_once(SBSERVICE);

/**
 *	@class MemberListWorkflow
 *	@desc Returns member key IDs in chain
 *
 *	@param chainid long int Chain ID [memory]
 *	@param type string Type name [memory] optional default 'general'
 *	@param pgsz long int Paging Size [memory] optional default false
 *	@param pgno long int Paging Index [memory] optional default 1
 *	@param total long int Paging Total [memory] optional default false
 *
 *	@return result array Member key information [memory]
 *	@return total long int Paging total [memory]
 *
 *	@author Vibhaj Rajan <vibhaj8@gmail.com>
 *
**/
class MemberListWorkflow implements Service {
	
	/**
	 *	@interface Service
	**/
	public function input(){
		return array(
			'required' => array('chainid'),
			'optional' => array('type' => 'general', 'pgsz' => false, 'pgno' => 0, 'total' => false)
		);
	}
	
	/**
	 *	@interface Service
	**/
	public function run($memory){
		$memory['msg'] = 'Member keys returned successfully';
		
		$service = array(
			'service' => 'transpera.relation.select.workflow',
			'args' => array('chainid', 'type'),
			'conn' => 'cbconn',
			'relation' => '`members`',
			'sqlprj' => '`keyid`',
			'sqlcnd' => "where `type`='\${type}' and `chainid`=\${chainid}",
			'escparam' => array('type'),
			'errormsg' => 'Invalid Chain ID / No Members'
		);
		
		return Snowblozm::run($service, $memory);
	}
	
	/**
	 *	@interface Service
	**/
	public function output(){
		return array('result', 'total');
	}
	
}

?>